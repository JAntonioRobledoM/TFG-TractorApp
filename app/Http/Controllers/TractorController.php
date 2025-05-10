<?php
// TractorController.php - Modified to include new fields from migration
namespace App\Http\Controllers;

use App\Models\Tractor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Validation\Rule;

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
        $availableUsers = User::all();
        return Inertia::render('Tractors/Create', [
            'availableUsers' => $availableUsers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'field_2' => ['nullable', 'numeric'],
            'brand' => ['nullable', 'string', 'max:255'],
            'license_plate' => ['nullable', 'string', 'unique:tractors'], 
            'color' => ['nullable', 'string', 'max:255'],
            'horsepower' => ['nullable', 'integer'],
            'working_hours' => ['nullable', 'numeric'],
            'is_available' => ['nullable', 'boolean'], 
            'users' => ['nullable', 'array']
        ]);

        $tractor = Tractor::create($validated);

        // Asignar usuarios si se proporcionan
        if (!empty($validated['users'])) {
            $tractor->owners()->sync($validated['users']);
        }

        return redirect()->route('tractors.index')
            ->with('message', 'Tractor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tractor $tractor)
    {
        $tractor->load(['owners', 'listings', 'aperos']); // Changed users to owners to match model relationship name
        
        return Inertia::render('Tractors/Show', [
            'tractor' => $tractor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tractor $tractor)
    {
        $availableUsers = User::all();
        $tractor->load('owners'); // Changed users to owners to match model relationship name
        return Inertia::render('Tractors/Edit', [
            'tractor' => $tractor,
            'availableUsers' => $availableUsers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tractor $tractor)
    {
        $validated = $request->validate([
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'field_2' => ['nullable', 'numeric'],
            'brand' => ['nullable', 'string', 'max:255'], // Added field
            'license_plate' => ['nullable', 'string', Rule::unique('tractors')->ignore($tractor->id)], // Added field
            'color' => ['nullable', 'string', 'max:255'], // Added field
            'horsepower' => ['nullable', 'integer'], // Added field
            'working_hours' => ['nullable', 'numeric'], // Added field
            'is_available' => ['nullable', 'boolean'], // Added field
            'users' => ['nullable', 'array']
        ]);

        $tractor->update($validated);

        // Sincronizar usuarios
        if (isset($validated['users'])) {
            $tractor->owners()->sync($validated['users']); // Changed users to owners to match model relationship name
        }

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
            'users' => $tractor->owners
        ]);
    }
    
    /**
     * Show the form for attaching aperos to a tractor.
     */
    public function attachAperos(Tractor $tractor)
    {
        $tractor->load('aperos');
        $availableAperos = \App\Models\Apero::whereNotIn('id', $tractor->aperos->pluck('id'))->get();
        
        return Inertia::render('Tractors/AttachAperos', [
            'tractor' => $tractor,
            'attachedAperos' => $tractor->aperos,
            'availableAperos' => $availableAperos
        ]);
    }
    
    /**
     * Attach an apero to a tractor.
     */
    public function attachApero(Request $request, Tractor $tractor)
    {
        $validated = $request->validate([
            'apero_id' => ['required', 'exists:aperos,id']
        ]);
        
        if (!$tractor->aperos()->where('apero_id', $validated['apero_id'])->exists()) {
            $tractor->aperos()->attach($validated['apero_id'], [
                'attached_at' => now(),
            ]);
            
            return redirect()->back()->with('message', 'Apero añadido al tractor correctamente');
        }
        
        return redirect()->back()->with('error', 'El apero ya está asignado a este tractor');
    }
    
    /**
     * Detach an apero from a tractor.
     */
    public function detachApero(Tractor $tractor, \App\Models\Apero $apero)
    {
        $pivotRow = $tractor->aperos()->where('apero_id', $apero->id)->first()->pivot;
        
        // Instead of detaching, update the detached_at timestamp
        $pivotRow->update([
            'detached_at' => now(),
        ]);
        
        return redirect()->back()->with('message', 'Apero desconectado del tractor');
    }
}