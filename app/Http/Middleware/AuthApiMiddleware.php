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

        // Verificar si el rol del usuario es '1', '2' o '0'
        $role = $request->session()->get('role');
        if ($role !== '1' && $role !== '2' && $role !== '0') {
            // Si el rol no es '1', '2' o '0', redirigir al usuario a la página de inicio de sesión con un mensaje de error
            return redirect()->route('login')->with('error', 'Rol no autorizado.');
        }

        // Verificar si el 'name' está presente en la sesión
        if (!$request->session()->has('name')) {
            // Obtener el nombre del usuario desde la solicitud o alguna otra fuente
            $name = $request->input('name');
            
            if ($name) {
                // Guardar el nombre en la sesión
                $request->session()->put('name', $name);
            } else {
                // Si no se puede obtener el nombre, redirigir al usuario a la página de inicio de sesión con un mensaje de error
                return redirect()->route('login')->with('error', 'No se pudo obtener el nombre del usuario.');
            }
        }


        // Verificar si el 'email' está presente en la sesión
        if (!$request->session()->has('email')) {
            // Obtener el correo electrónico del usuario desde la solicitud o alguna otra fuente
            $email = $request->input('email');
            
            if ($email) {
                // Guardar el correo electrónico en la sesión
                $request->session()->put('email', $email);
            } else {
                // Si no se puede obtener el correo electrónico, redirigir al usuario a la página de inicio de sesión con un mensaje de error
                return redirect()->route('login')->with('error', 'No se pudo obtener el correo electrónico del usuario.');
            }
        }
 
        
        // Verificar si el 'user_id' está presente en la sesión
        if (!$request->session()->has('user_id')) {
            // Obtener el 'user_id' del usuario desde la solicitud o alguna otra fuente
            $user_id = $request->input('user_id');
            
            if ($user_id) {
                // Guardar el 'user_id' en la sesión
                $request->session()->put('user_id', $user_id);
            } else {
                // Si no se puede obtener el 'user_id', redirigir al usuario a la página de inicio de sesión con un mensaje de error
                return redirect()->route('login')->with('error', 'No se pudo obtener el ID de usuario.');
            }
        }

        // Si todas las verificaciones son exitosas, continúa con la solicitud
        return $next($request);
    }
}
