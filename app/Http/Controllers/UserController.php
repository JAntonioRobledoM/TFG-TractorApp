<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
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
        
        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
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
        if ($request->filled('pass')) {
            $request->validate([
                'pass' => ['string', 'min:8'],
            ]);
            $validated['pass'] = Hash::make($request->pass);
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
        return Inertia::render('Users/Tractors', [
            'user' => $user,
            'tractors' => $user->tractors
        ]);
    }

    /**
     * Get the listings created by a specific user.
     */
    public function listings(User $user)
    {
        return Inertia::render('Users/Listings', [
            'user' => $user,
            'listings' => $user->listings
        ]);
    }
}