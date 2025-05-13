<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Muestra el formulario de contacto
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Public/Contact');
    }

    /**
     * Procesa el envío del formulario de contacto
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Aquí puedes implementar el código para enviar el email
        return redirect()->route('contact')->with('success', 'Tu mensaje ha sido enviado. Nos pondremos en contacto contigo pronto.');
    }
}