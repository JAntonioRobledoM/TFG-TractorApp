<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Request as RequestModel;
use App\Models\User;
use App\Notifications\NewRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestController extends Controller
{
    /**
     * Display a listing of the user's requests.
     */
    public function index()
    {
        $requests = RequestModel::where('requester_id', Auth::id())
            ->with(['listing.tractor', 'listing.seller'])
            ->latest()
            ->get();
            
        return Inertia::render('User/Requests/Index', [
            'requests' => $requests
        ]);
    }

    /**
     * Store a newly created request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => ['required', 'exists:listings,id'],
            'message' => ['nullable', 'string'],
        ]);

        // Check if the user is trying to request their own listing
        $listing = Listing::findOrFail($validated['listing_id']);
        if ($listing->seller_id === Auth::id()) {
            return back()->with('error', 'No puedes solicitar tu propio anuncio.');
        }

        // Check if the user already has a request for this listing
        $existingRequest = RequestModel::where('requester_id', Auth::id())
            ->where('listing_id', $validated['listing_id'])
            ->first();
            
        if ($existingRequest) {
            return back()->with('error', 'Ya has enviado una solicitud para este anuncio.');
        }

        // Create the request
        $newRequest = RequestModel::create([
            'requester_id' => Auth::id(),
            'listing_id' => $validated['listing_id'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        // Notify the seller about the new request
        $seller = User::find($listing->seller_id);
        $seller->notify(new NewRequestNotification($newRequest));

        return back()->with('message', 'Solicitud enviada correctamente.');
    }

    /**
     * Display the specified request.
     */
    public function show(RequestModel $request)
    {
        // Check if the user is the requester
        if ($request->requester_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }

        $request->load(['listing.tractor', 'listing.seller']);
        
        return Inertia::render('User/Requests/Show', [
            'request' => $request
        ]);
    }

    /**
     * Cancel the specified request.
     */
    public function cancel(RequestModel $request)
    {
        // Check if the user is the requester
        if ($request->requester_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta solicitud.');
        }

        // Check if the request is in a cancellable state
        if ($request->status !== 'pending') {
            return back()->with('error', 'Solo puedes cancelar solicitudes pendientes.');
        }

        // Update the request status
        $request->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('user.requests.index')
            ->with('message', 'Solicitud cancelada correctamente.');
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
 * Mark a request as completed.
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
}