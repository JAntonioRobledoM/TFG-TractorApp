<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apero;
use App\Models\Tractor;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AperoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aperos = Apero::all();
        return Inertia::render('Admin/Aperos/Index', [
            'aperos' => $aperos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Aperos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $apero = Apero::create($validated);

        return redirect()->route('aperos.index')
            ->with('message', 'Apero creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apero $apero)
    {
        $apero->load(['tractors']);
        
        return Inertia::render('Admin/Aperos/Show', [
            'apero' => $apero
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apero $apero)
    {
        return Inertia::render('Admin/Aperos/Edit', [
            'apero' => $apero
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apero $apero)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $apero->update($validated);

        return redirect()->route('aperos.index')
            ->with('message', 'Apero actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apero $apero)
    {
        $apero->delete();

        return redirect()->route('aperos.index')
            ->with('message', 'Apero eliminado correctamente.');
    }

    /**
     * Get all tractors that this apero is attached to.
     */
    public function tractors(Apero $apero)
    {
        return Inertia::render('Admin/Aperos/Tractors', [
            'apero' => $apero,
            'tractors' => $apero->tractors
        ]);
    }

    /**
     * Show the form for attaching the apero to tractors.
     */
    public function attachTractors(Apero $apero)
    {
        $apero->load('tractors');
        $availableTractors = Tractor::whereNotIn('id', $apero->tractors->pluck('id'))->get();
        
        return Inertia::render('Admin/Aperos/AttachTractors', [
            'apero' => $apero,
            'attachedTractors' => $apero->tractors,
            'availableTractors' => $availableTractors
        ]);
    }

    /**
     * Attach the apero to a tractor.
     */
    public function attachTractor(Request $request, Apero $apero)
    {
        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id']
        ]);
        
        if (!$apero->tractors()->where('tractor_id', $validated['tractor_id'])->exists()) {
            $apero->tractors()->attach($validated['tractor_id'], [
                'attached_at' => now(),
            ]);
            
            return redirect()->back()->with('message', 'Apero añadido al tractor correctamente');
        }
        
        return redirect()->back()->with('error', 'El apero ya está asignado a este tractor');
    }

    /**
     * Detach the apero from a tractor.
     */
    public function detachTractor(Apero $apero, Tractor $tractor)
    {
        $pivotRow = $apero->tractors()->where('tractor_id', $tractor->id)->first()->pivot;
        
        // Instead of detaching, update the detached_at timestamp
        $pivotRow->update([
            'detached_at' => now(),
        ]);
        
        return redirect()->back()->with('message', 'Apero desconectado del tractor');
    }
}