<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OutputController extends Controller {
    public function index(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API de salidas
        $apiUrl = $baseApiUrl . '/api/outputs';
        $apiSearchUrl = $baseApiUrl . '/api/searchOutput';
        $searchQuery = $request->input('query');

        // Parámetros de paginación
        $page = $request->input('page', 1); // Página actual, por defecto es 1
        $perPage = 10; // Número máximo de elementos por página

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Si hay un término de búsqueda, usar la URL de búsqueda
        if ($searchQuery) {
            $apiSearchUrl .= '?search=' . urlencode($searchQuery) . '&page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiSearchUrl);
        } else {
            $apiUrl .= '?page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiUrl);
        }

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $data = $response->json();

            // Verifica si la clave 'data' está presente en la respuesta
            if (is_array($data) && array_key_exists('data', $data)) {
                $outputs = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $outputs = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }

            // Pasa los datos de salidas y los parámetros de paginación a la vista y renderiza la vista
            return view('outputs.index', compact('outputs', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        }

        // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
        return redirect()->back()->with('error', 'Error al obtener las salidas de la API');
    }
}
