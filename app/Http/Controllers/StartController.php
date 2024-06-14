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
            'GetProductLoan' => $baseApiUrl . '/api/GetProductLoan',
            'GetEntrancesCount' => $baseApiUrl . '/api/GetEntrancesCount',
            'GetOutputsCount' => $baseApiUrl . '/api/GetOutputsCount',
            
        ];
        
        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Crear promesas para cada solicitud
        $promises = [];
        foreach ($apiUrls as $key => $url) {
            $promises[$key] = Http::withToken($token)->async()->get($url);
        }

        // Esperar a que todas las promesas se resuelvan
        $responses = collect($promises)->map(function ($promise) {
            return $promise->wait();
        });

        // Procesar las respuestas
        $data = [
            'products' => $responses->get('getCountProducts')->json() ?? ['count' => 'No se pudo obtener el número de productos.'],
            'entrance' => $responses->get('GetEntrancesCount')->json() ?? ['count' => 'No se pudo obtener el número de entradas.'],
            'out' => $responses->get('GetOutputsCount')->json() ?? ['count' => 'No se pudo obtener el número de salidas.'],
            'counts' => $responses->get('getCount')->json() ?? ['count' => 'No se pudo obtener el número de préstamos activos.'],
            'countsProductEntrance' => $responses->get('GetProductEntrance')->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más entradas.', 'total_quantity' => ''],
            'countsProductOut' => $responses->get('GetProductOutput')->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más salidas.', 'total_quantity' => ''],
            'countsProductLoan' => $responses->get('GetProductLoan')->json() ?? ['name' => 'No se pudo obtener el nombre del producto con más prestamos.', 'total_quantity' => '']
        ];

        return view('start.index', $data);
    }
}
