<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StartController extends Controller {
    public function index(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // Verificación de rol, solo permite acceso a usuarios con rol distinto de 1 o 2
        if (session('role') === '1' || session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // Construir las URLs completas para las solicitudes API
        $apiUrl = $baseApiUrl . '/api/getCount';
        $apiUrlProducts = $baseApiUrl . '/api/getCountProducts';
        $apiUrlEntrance = $baseApiUrl . '/api/GetProductEntrance';
        $apiUrlOut = $baseApiUrl . '/api/GetProductOutput';
        $apiUrlCountProductEntrance= $baseApiUrl . '/api/GetProductEntrance';
        $apiUrlCountProductOut= $baseApiUrl . '/api/GetProductOutput';
        $apiUrlCountProductLoan= $baseApiUrl . '/api/GetProductLoan';
        
        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza solicitudes HTTP GET a la API y obtén las respuestas
        $response = Http::withToken($token)->get($apiUrl);
        $responseProducts = Http::withToken($token)->get($apiUrlProducts);
        $responseEntrance = Http::withToken($token)->get($apiUrlEntrance);
        $responseOut = Http::withToken($token)->get($apiUrlOut);
        $responseCountProductEntrance = Http::withToken($token)->get($apiUrlCountProductEntrance);
        $responseCountProductOut = Http::withToken($token)->get($apiUrlCountProductOut);
        $responseCountProductLoan = Http::withToken($token)->get($apiUrlCountProductLoan);

        // Verifica si las solicitudes fueron exitosas
        if (
            $response->successful() && 
            $responseProducts->successful() && 
            $responseEntrance->successful() && 
            $responseOut->successful() && 
            $responseCountProductEntrance->successful() &&
            $responseCountProductLoan->successful() &&
            $responseCountProductOut->successful()
        ) {
            // Decodifica las respuestas JSON en arrays asociativos
            $counts = $response->json();
            $products = $responseProducts->json();
            $countsProductLoan = $responseCountProductLoan->json();
            $entrance = $responseEntrance->json();
            $out = $responseOut->json();
            $countsProductEntrance = $responseCountProductEntrance->json();
            $countsProductOut = $responseCountProductOut->json();

            // Pasa los datos a la vista y renderiza la vista
            return view('start.index', compact('counts', 'products', 'entrance', 'out', 'countsProductEntrance', 'countsProductOut', 'countsProductLoan'));
        } else {
            // Manejar el caso donde alguna solicitud no fue exitosa
            return back()->with('error', 'Hubo un problema al obtener los datos de la API. Inténtalo de nuevo.');
        }
    }
}
