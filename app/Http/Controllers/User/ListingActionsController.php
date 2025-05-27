<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingActionsController extends Controller
{
    /**
     * Store a newly created listing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
        ]);

        // Verificar que el usuario es propietario del tractor
        $tractor = \App\Models\Tractor::find($validated['tractor_id']);
        if (!$tractor) {
            return back()->withErrors(['tractor_id' => 'El tractor seleccionado no existe.']);
        }

        // Verificar si el usuario posee el tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['tractor_id' => 'No tienes permiso para publicar este tractor.']);
        }

        // Verificar que no hay otro anuncio activo para el mismo tractor
        $existingActiveListing = Listing::where('tractor_id', $validated['tractor_id'])
            ->where('is_active', true)
            ->first();

        if ($existingActiveListing) {
            return back()->withErrors(['tractor_id' => 'Ya existe un anuncio activo para este tractor.']);
        }

        // Agregar el ID del usuario actual
        $validated['seller_id'] = auth()->id();

        // Asegurar que is_active tenga un valor por defecto
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        try {
            // Crear el anuncio
            $listing = Listing::create($validated);

            return redirect()->route('user.dashboard')
                ->with('message', 'Anuncio creado correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al crear anuncio: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Hubo un error al crear el anuncio.']);
        }
    }

    /**
     * Update the specified listing.
     */
    public function update(Request $request, Listing $listing)
    {
        // Verificar que el usuario es propietario del anuncio
        if ($listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para modificar este anuncio.');
        }

        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
        ]);

        // Verificar que el usuario es propietario del tractor
        $tractor = \App\Models\Tractor::find($validated['tractor_id']);
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['tractor_id' => 'No tienes permiso para usar este tractor.']);
        }

        $listing->update($validated);

        return back()->with('message', 'Anuncio actualizado correctamente.');
    }

    /**
     * Remove the specified listing.
     */
    public function destroy(Listing $listing)
    {
        // Verificar que el usuario es propietario del anuncio
        if ($listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este anuncio.');
        }

        $listing->delete();

        return redirect()->route('user.dashboard')
            ->with('message', 'Anuncio eliminado correctamente.');
    }

    /**
     * Toggle the active status of a listing.
     */
    public function toggleStatus(Listing $listing)
    {
        // Verificar que el usuario es propietario del anuncio
        if ($listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para modificar este anuncio.');
        }

        // Cambiar el estado
        $listing->update([
            'is_active' => !$listing->is_active
        ]);

        return back()->with('message', 'El estado del anuncio se ha actualizado correctamente.');
    }
}