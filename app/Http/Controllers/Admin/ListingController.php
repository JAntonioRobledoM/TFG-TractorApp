<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Tractor;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::with(['tractor', 'seller'])->get();
        return Inertia::render('Admin/Listings/Index', [
            'listings' => $listings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tractors = Tractor::with('owners')->get();
        $users = User::all();
        
        return Inertia::render('Admin/Listings/Create', [
            'tractors' => $tractors,
            'users' => $users // AGREGADO para poder seleccionar vendedor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id'],
            'seller_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
        ]);

        // Verificar que no hay otro anuncio activo para el mismo tractor
        $existingActiveListing = Listing::where('tractor_id', $validated['tractor_id'])
            ->where('is_active', true)
            ->first();

        if ($existingActiveListing) {
            return back()->withErrors(['tractor_id' => 'Ya existe un anuncio activo para este tractor.']);
        }

        // Asegurar que is_active tenga un valor por defecto
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $listing = Listing::create($validated);

        return redirect()->route('listings.index')
            ->with('message', 'Anuncio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load(['tractor', 'seller', 'requests' => function($query) {
            $query->with('requester');
        }]);
        
        return Inertia::render('Admin/Listings/Show', [
            'listing' => $listing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        $tractors = Tractor::with('owners')->get();
        $users = User::all();
        
        return Inertia::render('Admin/Listings/Edit', [
            'listing' => $listing,
            'tractors' => $tractors,
            'users' => $users // AGREGADO para poder cambiar vendedor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id'],
            'seller_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:sale,rental'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'start_date' => ['nullable', 'date', 'required_if:type,rental'],
            'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
        ]);

        // Verificar que no hay otro anuncio activo para el mismo tractor (excluyendo el actual)
        $existingActiveListing = Listing::where('tractor_id', $validated['tractor_id'])
            ->where('is_active', true)
            ->where('id', '!=', $listing->id)
            ->first();

        if ($existingActiveListing) {
            return back()->withErrors(['tractor_id' => 'Ya existe otro anuncio activo para este tractor.']);
        }

        $listing->update($validated);

        return redirect()->route('listings.index')
            ->with('message', 'Anuncio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect()->route('listings.index')
            ->with('message', 'Anuncio eliminado correctamente.');
    }

    /**
     * Display all active sale listings.
     */
    public function sales()
    {
        $listings = Listing::where('type', 'sale')
            ->where('is_active', true)
            ->with(['tractor', 'seller'])
            ->get();
            
        return Inertia::render('Admin/Listings/Sales', [
            'listings' => $listings
        ]);
    }

    /**
     * Display all active rental listings.
     */
    public function rentals()
    {
        $listings = Listing::where('type', 'rental')
            ->where('is_active', true)
            ->with(['tractor', 'seller'])
            ->get();
            
        return Inertia::render('Admin/Listings/Rentals', [
            'listings' => $listings
        ]);
    }
    
    /**
     * Display all requests for a specific listing.
     */
    public function requests(Listing $listing)
    {
        $listing->load(['requests' => function($query) {
            $query->with('requester');
        }]);
        
        return Inertia::render('Admin/Listings/Requests', [
            'listing' => $listing,
            'requests' => $listing->requests
        ]);
    }
}