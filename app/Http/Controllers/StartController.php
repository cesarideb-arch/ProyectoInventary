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
        $apiUrls = [
            'getCount' => $baseApiUrl . '/api/getCount',
            'getCountProducts' => $baseApiUrl . '/api/getCountProducts',
            'GetProductEntrance' => $baseApiUrl . '/api/GetProductEntrance',
            'GetProductOutput' => $baseApiUrl . '/api/GetProductOutput',
            'GetProductLoan' => $baseApiUrl . '/api/GetProductLoan'
        ];
        
        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza solicitudes HTTP GET a la API y obtén las respuestas
        $responses = [];
        foreach ($apiUrls as $key => $url) {
            $responses[$key] = Http::withToken($token)->get($url);
        }

        // Inicializar datos con valores por defecto
        $data = [
            'counts' => $responses['getCount']->json() ?? ['count' => 'No se pudo obtener el número de préstamos.'],
            'products' => $responses['getCountProducts']->json() ?? ['count' => 'No se pudo obtener el número de productos.'],
            'entrance' => $responses['GetProductEntrance']->json() ?? ['total_quantity' => 'No se pudo obtener el número de entradas de productos.'],
            'out' => $responses['GetProductOutput']->json() ?? ['total_quantity' => 'No se pudo obtener el número de salidas de productos.'],
            'countsProductEntrance' => $responses['GetProductEntrance']->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más entradas.', 'total_quantity' => ''],
            'countsProductOut' => $responses['GetProductOutput']->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más salidas.', 'total_quantity' => ''],
            'countsProductLoan' => $responses['GetProductLoan']->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más prestamos.', 'total_quantity' => '']
        ];

        return view('start.index', $data);
    }
}
