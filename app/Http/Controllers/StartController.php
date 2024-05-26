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
        $apiUrlOutput = $baseApiUrl . '/api/GetProductOutput';
        $apiUrlLoan = $baseApiUrl . '/api/GetProductLoan';
        $apiUrlCountEntrances = $baseApiUrl . '/api/GetEntrancesCount';
        $apiUrlCountOutputs = $baseApiUrl . '/api/GetOutputsCount';
    
        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // Realiza las solicitudes HTTP GET a la API y obtén las respuestas
        $response = Http::withToken($token)->get($apiUrl);
        $responseProducts = Http::withToken($token)->get($apiUrlProducts);
        $responseEntrance = Http::withToken($token)->get($apiUrlEntrance);
        $responseOutput = Http::withToken($token)->get($apiUrlOutput);
        $responseLoan = Http::withToken($token)->get($apiUrlLoan);
        $responseCountEntrances = Http::withToken($token)->get($apiUrlCountEntrances);
        $responseCountOutputs = Http::withToken($token)->get($apiUrlCountOutputs);
    
        // Inicializar variables para los datos
        $entranceProduct = null;
        $outputProduct = null;
        $loanProduct = null;
        $countEntrances = null;
        $countOutputs = null;
        
        // Verifica si las solicitudes fueron exitosas
        if ($response->successful() && $responseProducts->successful() &&
            $responseEntrance->successful() && $responseOutput->successful() &&
            $responseLoan->successful() && $responseCountEntrances->successful() && $responseCountOutputs->successful()) {
    
            // Decodifica las respuestas JSON en arrays asociativos
            $counts = $response->json();
            $products = $responseProducts->json();
            $entranceProduct = $responseEntrance->json();
            $outputProduct = $responseOutput->json();
            $loanProduct = $responseLoan->json();
            $countEntrances = $responseCountEntrances->json();
            $countOutputs = $responseCountOutputs->json();
    
            // dd($counts, $products, $entranceProduct, $outputProduct, $loanProduct, $countEntrances, $countOutputs);

            // Pasa los datos a la vista y renderiza la vista
            return view('start.index', compact('counts', 'products', 'entranceProduct', 'outputProduct', 'loanProduct', 'countEntrances', 'countOutputs'));
        } else {
            // Manejar el caso donde alguna solicitud no fue exitosa
            return view('start.index', ['message' => 'Error al obtener datos de la API']);
        }
    }
}
