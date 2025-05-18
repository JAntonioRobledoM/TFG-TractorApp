<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListingController extends Controller
{
    /**
     * Display a listing of all active listings.
     */
    public function index()
    {
        $listings = Listing::with(['tractor', 'seller'])
            ->where('is_active', true)
            ->latest()
            ->get();
            
        return Inertia::render('User/Listings/Index', [
            'listings' => $listings
        ]);
    }

    /**
     * Display active sale listings.
     */
    public function sales()
    {
        $listings = Listing::where('type', 'sale')
            ->where('is_active', true)
            ->with(['tractor', 'seller'])
            ->latest()
            ->get();
            
        return Inertia::render('User/Listings/Index', [
            'listings' => $listings,
            'type' => 'sale'
        ]);
    }

    /**
     * Display active rental listings.
     */
    public function rentals()
    {
        $listings = Listing::where('type', 'rental')
            ->where('is_active', true)
            ->with(['tractor', 'seller'])
            ->latest()
            ->get();
            
        return Inertia::render('User/Listings/Index', [
            'listings' => $listings,
            'type' => 'rental'
        ]);
    }

    /**
     * Display the specified listing.
     */
    public function show(Listing $listing)
    {
        $listing->load(['tractor', 'seller']);
        
        // Check if the current user has already made a request for this listing
        $userRequest = null;
        if (auth()->check()) {
            $userRequest = $listing->requests()
                ->where('requester_id', auth()->id())
                ->first();
        }
        
        return Inertia::render('User/Listings/Show', [
            'listing' => $listing,
            'userRequest' => $userRequest
        ]);
    }

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