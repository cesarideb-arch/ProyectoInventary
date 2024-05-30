<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StartController extends Controller {
    public function index(Request $request) {
        // Obtener la URL base de la API desde la configuración
        $baseApiUrl = config('app.backend_api');

        // Construir las URLs completas para las solicitudes API
        $apiUrl = $baseApiUrl . '/api/getCount';
        $apiUrlProducts = $baseApiUrl . '/api/getCountProducts';
        $apiUrlEntrance = $baseApiUrl . '/api/GetProductEntrance';
        $apiUrlOut = $baseApiUrl . '/api/GetProductOutput';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza solicitudes HTTP GET a la API y obtén las respuestas
        $response = Http::withToken($token)->get($apiUrl);
        $responseProducts = Http::withToken($token)->get($apiUrlProducts);
        $responseEntrance = Http::withToken($token)->get($apiUrlEntrance);
        $responseOut = Http::withToken($token)->get($apiUrlOut);

        // Verifica si las solicitudes fueron exitosas
        if ($response->successful() && $responseProducts->successful() && $responseEntrance->successful() && $responseOut->successful()) {
            // Decodifica las respuestas JSON en arrays asociativos
            $counts = $response->json();
            $products = $responseProducts->json();
            $entrance = $responseEntrance->json();
            $out = $responseOut->json();

            // Pasa los datos a la vista y renderiza la vista
            return view('start.index', compact('counts', 'products', 'entrance', 'out'));
        } else {
            // Manejar el caso donde alguna solicitud no fue exitosa
            return view('start.index', ['message' => 'Error al obtener datos de la API']);
        }
    }
}
