<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StartController extends Controller {
    public function index(Request $request) {
        // URL de la API de préstamos
        $apiUrl = 'http://127.0.0.1:8000/api/getCount';
        $apiUrlProducts = 'http://127.0.0.1:8000/api/getCountProducts';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::withToken($token)->get($apiUrl);
        $responseProducts = Http::withToken($token)->get($apiUrlProducts);

        // Verifica si la solicitud fue exitosa
        if ($response->successful() && $responseProducts->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $counts = $response->json();
            $products = $responseProducts->json();

            // Pasa los datos de préstamos a la vista y renderiza la vista
            return view('start.index', compact('counts', 'products'));
        } else {
            // Manejar el caso donde la solicitud no fue exitosa
            return view('start.index', ['message' => 'Error al obtener datos de la API']);
        }
    }
}

