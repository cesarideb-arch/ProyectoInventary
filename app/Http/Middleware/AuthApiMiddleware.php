<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthApiMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // Verificar si el token de la sesión está presente
        if (!$request->session()->has('token')) {
            // Si el token no está presente, redirigir al usuario a la página de inicio de sesión
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }



        // Verificar si el rol del usuario es '1' o '2'
        $role = $request->session()->get('role');
        if ($role !== '1' && $role !== '2' && $role !== '0') {
            // Si el rol no es '1' o '2', redirigir al usuario a la página de inicio de sesión con un mensaje de error
            return redirect()->route('login')->with('error', 'Rol no autorizado.');
        }

        return $next($request);
    }
}