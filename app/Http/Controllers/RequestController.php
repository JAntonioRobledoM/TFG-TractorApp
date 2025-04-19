<?php

namespace App\Http\Controllers;

use App\Models\Request as TractorRequest;
use App\Models\Listing;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = TractorRequest::with(['listing.tractor', 'requester'])->get();
        return Inertia::render('Requests/Index', [
            'requests' => $requests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $listing = null;
        if ($request->has('listing_id')) {
            $listing = Listing::with(['tractor', 'seller'])->findOrFail($request->listing_id);
        }
        
        return Inertia::render('Requests/Create', [
            'listing' => $listing,
            'listings' => Listing::where('is_active', true)->with(['tractor', 'seller'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => ['required', 'exists:listings,id'],
            'requester_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'status' => ['required', 'string', 'in:pending,accepted,rejected,completed'],
            'offered_price' => ['nullable', 'numeric', 'min:0'],
            'requested_start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'requested_end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:requested_start_date'],
            'message' => ['nullable', 'string'],
        ]);
        
        $tractorRequest = TractorRequest::create($validated);

        // Create notification for the listing seller
        $listing = Listing::findOrFail($validated['listing_id']);
        Notification::create([
            'user_id' => $listing->seller_id,
            'related_id' => $tractorRequest->id,
            'related_type' => 'request',
            'type' => 'new_request',
            'message' => 'Nueva solicitud para tu anuncio',
            'is_read' => false
        ]);

        return redirect()->route('requests.index')
            ->with('message', 'Solicitud creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TractorRequest $request)
    {
        $request->load(['listing.tractor', 'listing.seller', 'requester']);
        
        return Inertia::render('Requests/Show', [
            'request' => $request
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TractorRequest $request)
    {
        $request->load(['listing.tractor', 'requester']);
        
        return Inertia::render('Requests/Edit', [
            'request' => $request,
            'listings' => Listing::where('is_active', true)->with(['tractor', 'seller'])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $httpRequest, TractorRequest $request)
    {
        $validated = $httpRequest->validate([
            'listing_id' => ['required', 'exists:listings,id'],
            'requester_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'status' => ['required', 'string', 'in:pending,accepted,rejected,completed'],
            'offered_price' => ['nullable', 'numeric', 'min:0'],
            'requested_start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'requested_end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:requested_start_date'],
            'message' => ['nullable', 'string'],
        ]);

        $oldStatus = $request->status;
        $request->update($validated);

        // Create notification if status changed
        if ($oldStatus != $validated['status']) {
            Notification::create([
                'user_id' => $request->requester_id,
                'related_id' => $request->id,
                'related_type' => 'request',
                'type' => 'status_change',
                'message' => 'El estado de tu solicitud ha cambiado a: ' . $validated['status'],
                'is_read' => false
            ]);
        }

        return redirect()->route('requests.index')
            ->with('message', 'Solicitud actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TractorRequest $request)
    {
        $request->delete();

        return redirect()->route('requests.index')
            ->with('message', 'Solicitud eliminada correctamente.');
    }

    /**
     * Accept a request.
     */
    public function accept(TractorRequest $request)
    {
        $request->update(['status' => 'accepted']);
        
        // Create notification
        Notification::create([
            'user_id' => $request->requester_id,
            'related_id' => $request->id,
            'related_type' => 'request',
            'type' => 'request_accepted',
            'message' => 'Tu solicitud ha sido aceptada',
            'is_read' => false
        ]);

        return redirect()->route('requests.show', $request)
            ->with('message', 'Solicitud aceptada correctamente.');
    }
    
    /**
     * Reject a request.
     */
    public function reject(TractorRequest $request)
    {
        $request->update(['status' => 'rejected']);
        
        // Create notification
        Notification::create([
            'user_id' => $request->requester_id,
            'related_id' => $request->id,
            'related_type' => 'request',
            'type' => 'request_rejected',
            'message' => 'Tu solicitud ha sido rechazada',
            'is_read' => false
        ]);

        return redirect()->route('requests.show', $request)
            ->with('message', 'Solicitud rechazada correctamente.');
    }
    
    /**
     * Complete a request.
     */
    public function complete(TractorRequest $request)
    {
        $request->update(['status' => 'completed']);
        
        // Create notification
        Notification::create([
            'user_id' => $request->requester_id,
            'related_id' => $request->id,
            'related_type' => 'request',
            'type' => 'request_completed',
            'message' => 'Tu solicitud ha sido completada',
            'is_read' => false
        ]);

        return redirect()->route('requests.show', $request)
            ->with('message', 'Solicitud completada correctamente.');
    }
}