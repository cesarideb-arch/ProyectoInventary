<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller {

    public function index() {
        // Primero, muestra la vista de inicio de sesión
        return view('login.index');
    }
    public function login(Request $request) {
        // URL completa del endpoint de login en localhost:8000
        $loginUrl = 'http://localhost:8000/api/login';

        // Haz la solicitud POST al endpoint de login
        $response = Http::post($loginUrl, [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Verificar la respuesta de la API
        if ($response->successful()) {
            // Obtener los datos de la respuesta
            $responseData = $response->json();

            // Verificar si la respuesta contiene el token y la información del usuario
            if (isset($responseData['token']) && isset($responseData['user'])) {
                // Si la respuesta contiene los datos esperados, almacenar el token en la sesión
                $token = $responseData['token'];
                $user = $responseData['user'];

                // Almacena el token en la sesión (esto es solo un ejemplo)
                $request->session()->put('token', $token);

                // Redirige al usuario a la página de productos
                return redirect()->route('start.index');
            } else {
                // Si la respuesta no contiene los datos esperados, muestra un mensaje de error
                return back()->with('error', 'La respuesta de la API es incorrecta. Inténtalo de nuevo.');
            }
        } else {
            // Si la solicitud no fue exitosa, muestra un mensaje de error
            return back()->with('error', 'Contraseña incorrecta o correo electrónico inválido. Inténtalo de nuevo.');
        }
    }
    public function logout(Request $request) {
        // Elimina el token de la sesión
        $request->session()->forget('token');

        // Redirige al usuario a la página de inicio de sesión
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
