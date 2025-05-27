<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TractorController extends Controller
{
    /**
     * Display a listing of user's tractors.
     */
    public function index()
    {
        return Inertia::render('User/Tractors/Index', [
            'tractors' => auth()->user()->tractors()->latest()->get()
        ]);
    }

    /**
     * Display the specified tractor.
     */
    public function show(Tractor $tractor)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para ver este tractor.');
        }

        // Cargar aperos conectados con información de pivot
        $tractor->load([
            'aperos' => function ($query) {
                $query->withPivot('attached_at', 'detached_at');
            }
        ]);

        return Inertia::render('User/Tractors/Show', [
            'tractor' => $tractor
        ]);
    }

    /**
     * Store a newly created tractor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'horsepower' => ['nullable', 'integer'],
            'working_hours' => ['nullable', 'numeric'],
            'is_available' => ['nullable', 'in:0,1,true,false'], // Permitir valores múltiples
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Convertir is_available a boolean
        $validated['is_available'] = filter_var($validated['is_available'] ?? true, FILTER_VALIDATE_BOOLEAN);

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tractors', 'public');
            $validated['image'] = $imagePath;
        }

        // Crear el tractor
        $tractor = Tractor::create($validated);

        // Asignar al usuario actual
        $tractor->owners()->attach(auth()->id());

        return redirect()->route('user.dashboard')
            ->with('message', 'Tractor creado correctamente.');
    }

    /**
     * Update the specified tractor.
     */
    public function update(Request $request, Tractor $tractor)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para editar este tractor.');
        }

        $validated = $request->validate([
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'horsepower' => ['nullable', 'integer'],
            'working_hours' => ['nullable', 'numeric'],
            'is_available' => ['nullable', 'in:0,1,true,false'], // Permitir valores múltiples
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        // Convertir is_available a boolean
        $validated['is_available'] = filter_var($validated['is_available'] ?? $tractor->is_available, FILTER_VALIDATE_BOOLEAN);

        // Manejar la imagen
        if ($request->has('remove_image') && $request->remove_image) {
            // Eliminar imagen existente
            if ($tractor->image) {
                Storage::delete($tractor->image);
                $validated['image'] = null;
            }
        } elseif ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($tractor->image) {
                Storage::delete($tractor->image);
            }

            // Subir nueva imagen
            $imagePath = $request->file('image')->store('tractors', 'public');
            $validated['image'] = $imagePath;
        }

        // Remover el campo remove_image de los datos validados
        unset($validated['remove_image']);

        $tractor->update($validated);

        return redirect()->route('user.dashboard')
            ->with('message', 'Tractor actualizado correctamente.');
    }

    /**
     * Remove the specified tractor.
     */
    public function destroy(Tractor $tractor)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para eliminar este tractor.');
        }

        try {
            // 1. Verificar si el tractor está siendo usado en algún anuncio activo
            $activeListings = $tractor->listings()->where('is_active', true)->count();
            if ($activeListings > 0) {
                return back()->with('error', 'No puedes eliminar este tractor porque está siendo usado en anuncios activos.');
            }

            // 2. Eliminar primero todas las relaciones del usuario actual
            $tractor->owners()->detach(auth()->id());

            // 3. Si no hay más propietarios, eliminar todas las relaciones y el tractor
            if ($tractor->owners()->count() === 0) {
                // Eliminar relaciones con aperos (opcional: solo marcar como desconectado)
                if ($tractor->aperos()->count() > 0) {
                    $aperoIds = $tractor->aperos()->pluck('aperos.id');
                    foreach ($aperoIds as $aperoId) {
                        $tractor->aperos()->updateExistingPivot($aperoId, ['detached_at' => now()]);
                    }
                }

                // Eliminar anuncios inactivos relacionados
                $tractor->listings()->where('is_active', false)->delete();

                // Finalmente eliminar el tractor
                $tractor->delete();

                return redirect()->route('user.tractors.index')
                    ->with('message', 'Tractor eliminado completamente.');
            }

            return redirect()->route('user.tractors.index')
                ->with('message', 'Tu relación con el tractor ha sido eliminada.');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar tractor: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al eliminar el tractor. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Update working hours for a specific tractor.
     */
    public function updateHours(Request $request, Tractor $tractor)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para editar este tractor.');
        }

        $validated = $request->validate([
            'working_hours' => ['required', 'numeric', 'min:0'],
        ]);

        $tractor->update([
            'working_hours' => $validated['working_hours'],
        ]);

        return back()->with('message', 'Horas de trabajo actualizadas correctamente.');
    }

    /**
     * Attach an apero to a tractor.
     */
    public function attachApero(Request $request, Tractor $tractor)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para modificar este tractor.');
        }

        $validated = $request->validate([
            'apero_id' => ['required', 'exists:aperos,id']
        ]);

        $apero = \App\Models\Apero::find($validated['apero_id']);

        // Verificar si el apero está disponible
        if (!$apero->is_available) {
            return back()->with('error', 'El apero seleccionado no está disponible.');
        }

        // Verificar si el apero ya está conectado a este tractor
        $existingConnection = $tractor->aperos()
            ->where('apero_id', $validated['apero_id'])
            ->wherePivot('detached_at', null)
            ->exists();

        if ($existingConnection) {
            return back()->with('error', 'El apero ya está conectado a este tractor.');
        }

        // Conectar el apero
        $tractor->aperos()->attach($validated['apero_id'], [
            'attached_at' => now(),
            'detached_at' => null,
        ]);

        return back()->with('message', 'Apero conectado al tractor correctamente.');
    }

    /**
     * Detach an apero from a tractor.
     */
    public function detachApero(Tractor $tractor, \App\Models\Apero $apero)
    {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para modificar este tractor.');
        }

        // Buscar la relación pivot activa
        $pivotRecord = $tractor->aperos()
            ->where('apero_id', $apero->id)
            ->wherePivot('detached_at', null)
            ->first();

        if (!$pivotRecord) {
            return back()->with('error', 'El apero no está conectado a este tractor.');
        }

        // Actualizar la fecha de desconexión en lugar de eliminar la relación
        $tractor->aperos()->updateExistingPivot($apero->id, [
            'detached_at' => now(),
        ]);

        return back()->with('message', 'Apero desconectado del tractor correctamente.');
    }

    /**
     * Get user's tractors API.
     */
    public function getUserTractors()
    {
        $user = auth()->user();
        $tractors = $user->tractors()->latest()->get();

        return response()->json([
            'tractors' => $tractors
        ]);
    }
}