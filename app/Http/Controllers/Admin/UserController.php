<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tractor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'tlf' => ['nullable', 'numeric'],
            'pass' => ['required', 'string', 'min:8'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'is_admin' => ['nullable', 'boolean'],
            'pfp' => ['nullable', 'string', 'max:255'],
        ]);

        // Hash password
        $validated['pass'] = Hash::make($validated['pass']); 
        unset($validated['password']); 

        $user = User::create($validated);

        return redirect()->route('users.index')
            ->with('message', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['tractors', 'listings', 'requests']);
        
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'availableTractors' => Tractor::whereNotIn('id', $user->tractors->pluck('id'))->get()
        ]);
    }

    public function addTractor(Request $request, User $user)
    {
        $validated = $request->validate([
            'tractor_id' => ['required', 'exists:tractors,id']
        ]);

        // Verificar si el tractor ya está asignado
        if (!$user->tractors()->where('tractor_id', $validated['tractor_id'])->exists()) {
            $user->tractors()->attach($validated['tractor_id']);

            return redirect()->back()->with('message', 'Tractor añadido al usuario correctamente');
        }

        return redirect()->back()->with('error', 'El tractor ya está asignado a este usuario');
    }

    /**
     * Remove a tractor from a user.
     */
    public function removeTractor(User $user, Tractor $tractor)
    {
        $user->tractors()->detach($tractor->id);

        return redirect()->back()->with('message', 'Tractor eliminado del usuario');
    }

    /**
     * Show the form for assigning tractors to a user.
     */
    public function assignTractors(User $user)
    {
        $assignedTractors = $user->tractors;
        $availableTractors = Tractor::whereNotIn('id', $assignedTractors->pluck('id'))->get();

        return Inertia::render('Admin/Users/AssignTractors', [
            'user' => $user,
            'assignedTractors' => $assignedTractors,
            'availableTractors' => $availableTractors
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'tlf' => ['nullable', 'numeric'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'is_admin' => ['nullable', 'boolean'],
            'pfp' => ['nullable', 'string', 'max:255'],
        ]);

        // Update password if provided
        if ($request->filled('password')) { // Changed from 'pass' to 'password'
            $request->validate([
                'password' => ['string', 'min:8'], // Changed from 'pass' to 'password'
            ]);
            $validated['pass'] = Hash::make($request->password); // Store hashed password in 'pass' field
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('message', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('message', 'Usuario eliminado correctamente.');
    }

    /**
     * Get the tractors owned by a specific user.
     */
    public function tractors(User $user)
    {
        return Inertia::render('Admin/Users/Tractors', [
            'user' => $user,
            'tractors' => $user->tractors
        ]);
    }

    /**
     * Get the listings created by a specific user.
     */
    public function listings(User $user)
    {
        return Inertia::render('Admin/Users/Listings', [
            'user' => $user,
            'listings' => $user->listings
        ]);
    }
}