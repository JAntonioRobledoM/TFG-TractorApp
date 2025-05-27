<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Apero;
use App\Models\Tractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AperoController extends Controller
{
    /**
     * Display a listing of user's aperos.
     */
    public function index()
    {
        $user = auth()->user();

        // Obtener todos los aperos que pertenecen al usuario
        // mediante la relación directa 'user_id' y también los que están conectados a sus tractores
        $aperosByUserId = Apero::where('user_id', $user->id)->get();

        $aperosByTractors = Apero::whereHas('tractors', function ($query) use ($user) {
            $query->whereHas('owners', function ($ownerQuery) use ($user) {
                $ownerQuery->where('user_id', $user->id);
            });
        })->get();

        // Combinar ambos conjuntos y eliminar duplicados
        $aperos = $aperosByUserId->merge($aperosByTractors)->unique('id');

        // Cargar las relaciones de tractores para los aperos
        $aperoIds = $aperos->pluck('id')->toArray();
        $aperos = Apero::whereIn('id', $aperoIds)
            ->with([
                'tractors' => function ($query) use ($user) {
                    $query->whereHas('owners', function ($ownerQuery) use ($user) {
                        $ownerQuery->where('user_id', $user->id);
                    });
                }
            ])->get();

        return Inertia::render('User/Aperos/Index', [
            'aperos' => $aperos
        ]);
    }

    /**
     * Display the specified apero.
     */
    public function show(Apero $apero)
    {
        $user = auth()->user();

        // Verificar que el usuario es propietario del apero o tiene acceso a través de sus tractores
        $hasAccess = $apero->user_id === $user->id ||
            $apero->tractors()->whereHas('owners', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();

        if (!$hasAccess) {
            abort(403, 'No tienes permiso para ver este apero.');
        }

        // Cargar las relaciones de tractores
        $apero->load([
            'tractors' => function ($query) use ($user) {
                $query->whereHas('owners', function ($ownerQuery) use ($user) {
                    $ownerQuery->where('user_id', $user->id);
                });
            }
        ]);

        return Inertia::render('User/Aperos/Show', [
            'apero' => $apero
        ]);
    }

    /**
     * Store a newly created apero.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'description' => 'nullable|string',
            'tractor_id' => 'nullable|exists:tractors,id', // Ahora es nullable
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('aperos', 'public');
            $validated['image'] = $imagePath;
        }

        // Crear apero con el ID del usuario actual
        $apero = Apero::create([
            'name' => $validated['name'],
            'type' => $validated['type'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'year' => $validated['year'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_available' => true,
            'image' => $validated['image'] ?? null,
            'user_id' => auth()->id(), // Añadir el ID del usuario autenticado
        ]);

        // Conectar al tractor si se proporcionó un tractor_id
        if (!empty($validated['tractor_id'])) {
            $tractor = Tractor::findOrFail($validated['tractor_id']);

            // Verificar que el tractor pertenece al usuario
            if ($tractor->owners()->where('user_id', auth()->id())->exists()) {
                $tractor->aperos()->attach($apero->id, ['attached_at' => now()]);
            }
        }

        return redirect()->route('user.dashboard')->with('message', 'Apero creado correctamente.');
    }

    /**
     * Update the specified apero.
     */
    public function update(Request $request, Apero $apero)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'description' => 'nullable|string',
            'tractor_id' => 'nullable|exists:tractors,id', // Cambiado a nullable
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'], // Para eliminar imagen existente
        ]);

        // Si se proporcionó un tractor_id, verificar que el usuario tiene permiso sobre el tractor
        if (!empty($validated['tractor_id'])) {
            $tractor = Tractor::findOrFail($validated['tractor_id']);
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para usar este tractor.');
            }
        }

        // Manejar la imagen
        if ($request->has('remove_image') && $request->remove_image) {
            // Eliminar imagen existente
            if ($apero->image) {
                Storage::delete($apero->image);
                $validated['image'] = null;
            }
        } elseif ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($apero->image) {
                Storage::delete($apero->image);
            }

            // Subir nueva imagen
            $imagePath = $request->file('image')->store('aperos', 'public');
            $validated['image'] = $imagePath;
        }

        // Guardar el tractor_id antes de removerlo de los datos validados
        $tractorId = $validated['tractor_id'] ?? null;

        // Remover campos que no van al modelo
        unset($validated['tractor_id'], $validated['remove_image']);

        // Actualizar campos del apero
        $apero->update($validated);

        // Manejar la relación con el tractor si se proporcionó uno
        if ($tractorId) {
            $tractor = Tractor::findOrFail($tractorId);

            // Verificar si ya existe una conexión con este tractor
            $existingConnection = $apero->tractors()
                ->where('tractor_id', $tractorId)
                ->wherePivot('detached_at', null)
                ->exists();

            if (!$existingConnection) {
                // Conectar el apero al tractor
                $tractor->aperos()->attach($apero->id, ['attached_at' => now()]);
            }
        }

        return redirect()->route('user.dashboard')->with('message', 'Apero actualizado correctamente.');
    }

    /**
     * Get available aperos for a specific tractor.
     */
    public function getAvailableForTractor(Tractor $tractor)
    {
        $user = auth()->user();

        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', $user->id)->exists()) {
            abort(403, 'No tienes permiso para ver este tractor.');
        }

        // Obtener aperos que no están conectados a este tractor
        $connectedAperoIds = $tractor->aperos()
            ->wherePivot('detached_at', null)
            ->pluck('aperos.id');

        $availableAperos = Apero::where('is_available', true)
            ->whereNotIn('id', $connectedAperoIds)
            ->get();

        return response()->json([
            'aperos' => $availableAperos
        ]);
    }
}