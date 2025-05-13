<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado y es administrador
        // Comprobamos explícitamente que is_admin sea exactamente igual a 1 (true)
        if (!$request->user() || $request->user()->is_admin != 1) {
            // Redirigir a la página de inicio con mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta área.');
        }

        return $next($request);
    }
}
