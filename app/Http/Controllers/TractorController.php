<?php

namespace App\Http\Controllers;

use App\Models\Tractor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tractors = Tractor::all();
        return Inertia::render('Tractors/Index', [
            'tractors' => $tractors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tractors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_2' => ['nullable', 'numeric'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ]);

        $tractor = Tractor::create($validated);

        return redirect()->route('tractors.index')
            ->with('message', 'Tractor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tractor $tractor)
    {
        $tractor->load(['users', 'listings']);
        
        return Inertia::render('Tractors/Show', [
            'tractor' => $tractor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tractor $tractor)
    {
        return Inertia::render('Tractors/Edit', [
            'tractor' => $tractor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tractor $tractor)
    {
        $validated = $request->validate([
            'field_2' => ['nullable', 'numeric'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ]);

        $tractor->update($validated);

        return redirect()->route('tractors.index')
            ->with('message', 'Tractor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tractor $tractor)
    {
        $tractor->delete();

        return redirect()->route('tractors.index')
            ->with('message', 'Tractor eliminado correctamente.');
    }

    /**
     * Get all listings for a specific tractor.
     */
    public function listings(Tractor $tractor)
    {
        return Inertia::render('Tractors/Listings', [
            'tractor' => $tractor,
            'listings' => $tractor->listings
        ]);
    }

    /**
     * Get all users who own this tractor.
     */
    public function users(Tractor $tractor)
    {
        return Inertia::render('Tractors/Users', [
            'tractor' => $tractor,
            'users' => $tractor->users
        ]);
    }
}