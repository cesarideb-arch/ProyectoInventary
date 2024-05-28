<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StartController extends Controller {
    public function index(Request $request) {
        // Obtener la URL base de la API desde la configuración
        $baseApiUrl = config('app.backend_api');

        // Lista de endpoints de la API
        $endpoints = [
            'counts' => '/api/getCount',
            'products' => '/api/getCountProducts',
            'entranceProduct' => '/api/GetProductEntrance',
            'outputProduct' => '/api/GetProductOutput',
            'loanProduct' => '/api/GetProductLoan',
            'countEntrances' => '/api/GetEntrancesCount',
            'countOutputs' => '/api/GetOutputsCount'
        ];

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Inicializar un array para almacenar las respuestas
        $responses = [];

        // Realiza las solicitudes HTTP GET a la API y almacena las respuestas
        foreach ($endpoints as $key => $endpoint) {
            $response = Http::withToken($token)->get($baseApiUrl . $endpoint);
            if ($response->successful()) {
                $responses[$key] = $response->json();
            } else {
                // Manejar el caso donde alguna solicitud no fue exitosa
                return view('start.index', ['message' => 'Error al obtener datos de la API']);
            }
        }
           // Agregar dd() para ver el contenido de $responses
    // dd($responses);

        // Pasa los datos a la vista y renderiza la vista
        return view('start.index', $responses);
    }
}
