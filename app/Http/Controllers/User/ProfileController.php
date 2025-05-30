<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado.
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        return Inertia::render('User/Profile/Show', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Mostrar el formulario para editar el perfil.
     *
     * @return \Inertia\Response
     */
    public function edit()
    {
        return Inertia::render('User/Profile/Edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Actualizar el perfil del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'tlf' => ['nullable', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'pfp' => ['nullable', 'image', 'max:2048'],
        ]);

        // Verificar contraseña actual si se intenta cambiar la contraseña
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->pass)) {
                return back()->withErrors([
                    'current_password' => 'La contraseña actual no es correcta.',
                ]);
            }
            
            $user->pass = Hash::make($validated['new_password']);
        }

        // Actualizar foto de perfil si se proporcionó una nueva
        if ($request->hasFile('pfp')) {
            // Eliminar la foto anterior si existe
            if ($user->pfp && Storage::exists($user->pfp)) {
                Storage::delete($user->pfp);
            }
            
            // Guardar la nueva foto
            $path = $request->file('pfp')->store('public/profile_photos');
            $user->pfp = str_replace('public/', '', $path);
        }

        // Actualizar los demás campos
        $user->username = $validated['username'];
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->dni = $validated['dni'];
        $user->tlf = $validated['tlf'];
        $user->email = $validated['email'];
        
        $user->save();

        return redirect()->route('user.profile.show')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Eliminar la foto de perfil del usuario.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProfilePhoto()
    {
        $user = Auth::user();

        if ($user->pfp && Storage::exists('public/' . $user->pfp)) {
            Storage::delete('public/' . $user->pfp);
        }

        $user->pfp = null;
        $user->save();

        return back()->with('success', 'Foto de perfil eliminada correctamente.');
    }
}