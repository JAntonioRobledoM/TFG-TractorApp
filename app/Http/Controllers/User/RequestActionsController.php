<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class RequestActionsController extends Controller
{
    /**
     * Store a newly created request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => ['required', 'exists:listings,id'],
            'message' => ['nullable', 'string'],
            'offered_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Obtener el listing para determinar el tipo
        $listing = \App\Models\Listing::findOrFail($validated['listing_id']);

        // Check if the user is trying to request their own listing
        if ($listing->seller_id === auth()->id()) {
            return back()->with('error', 'No puedes solicitar tu propio anuncio.');
        }

        // Check if the user already has a request for this listing
        $existingRequest = RequestModel::where('requester_id', auth()->id())
            ->where('listing_id', $validated['listing_id'])
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Ya has enviado una solicitud para este anuncio.');
        }

        // Si no se proporciona el precio ofrecido, usar el precio del anuncio
        if (!isset($validated['offered_price']) || $validated['offered_price'] == '') {
            $validated['offered_price'] = $listing->price;
        }

        // Crear la solicitud
        $newRequest = RequestModel::create([
            'requester_id' => auth()->id(),
            'listing_id' => $validated['listing_id'],
            'message' => $validated['message'] ?? null,
            'type' => $listing->type, // Usar el mismo tipo que el anuncio (sale o rental)
            'status' => 'pending',
            'offered_price' => $validated['offered_price'],
            // Para solicitudes de alquiler, usar las fechas del anuncio por defecto
            'requested_start_date' => $listing->type === 'rental' ? $listing->start_date : null,
            'requested_end_date' => $listing->type === 'rental' ? $listing->end_date : null,
        ]);

        return back()->with('message', 'Solicitud enviada correctamente.');
    }

    /**
     * Accept a request.
     */
    public function accept(RequestModel $request)
    {
        // Verificar que el usuario es el propietario del anuncio
        $listing = $request->listing;
        if (!$listing || $listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para aceptar esta solicitud.');
        }

        // Verificar que la solicitud está pendiente
        if ($request->status !== 'pending') {
            return back()->with('error', 'Solo puedes aceptar solicitudes pendientes.');
        }

        // Actualizar el estado de la solicitud
        $request->update([
            'status' => 'accepted'
        ]);

        return back()->with('message', 'Solicitud aceptada correctamente.');
    }

    /**
     * Reject a request.
     */
    public function reject(RequestModel $request)
    {
        // Verificar que el usuario es el propietario del anuncio
        $listing = $request->listing;
        if (!$listing || $listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para rechazar esta solicitud.');
        }

        // Verificar que la solicitud está pendiente
        if ($request->status !== 'pending') {
            return back()->with('error', 'Solo puedes rechazar solicitudes pendientes.');
        }

        // Actualizar el estado de la solicitud
        $request->update([
            'status' => 'rejected'
        ]);

        return back()->with('message', 'Solicitud rechazada correctamente.');
    }

    /**
     * Complete a request.
     */
    public function complete(RequestModel $request)
    {
        // Verificar que el usuario es el propietario del anuncio o el solicitante
        $listing = $request->listing;
        if (!$listing || ($listing->seller_id !== auth()->id() && $request->requester_id !== auth()->id())) {
            abort(403, 'No tienes permiso para completar esta solicitud.');
        }

        // Verificar que la solicitud está aceptada
        if ($request->status !== 'accepted') {
            return back()->with('error', 'Solo puedes completar solicitudes aceptadas.');
        }

        // Actualizar el estado de la solicitud
        $request->update([
            'status' => 'completed'
        ]);

        // Si es una solicitud de compra, desactivar el anuncio
        if ($request->type === 'sale') {
            $listing->update([
                'is_active' => false
            ]);
        }

        return back()->with('message', 'Solicitud marcada como completada correctamente.');
    }

    /**
     * Remove the specified request.
     */
    public function destroy(RequestModel $request)
    {
        // Verificar que el usuario es el propietario de la solicitud
        if ($request->requester_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta solicitud.');
        }

        $request->delete();

        return redirect()->route('user.dashboard')
            ->with('message', 'Solicitud eliminada correctamente.');
    }
}