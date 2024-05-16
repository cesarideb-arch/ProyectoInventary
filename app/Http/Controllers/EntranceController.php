<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntranceController extends Controller
{
    public function index(Request $request) {
        // URL de la API de entradas
        $apiUrl = 'http://127.0.0.1:8000/api/entrances';
        $apiSearchUrl = 'http://127.0.0.1:8000/api/searchEntrance';
        $searchQuery = $request->input('query');
    
        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        // Si hay un término de búsqueda, usar la URL de búsqueda
        if ($searchQuery) {
            $apiSearchUrl .= '?search=' . urlencode($searchQuery);
            $response = Http::get($apiSearchUrl);
        } else {
            $response = Http::get($apiUrl);
        }

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $entrances = $response->json();
    
            // Pasa los datos de entradas a la vista y renderiza la vista
            return view('entrances.index', compact('entrances'));
        }
    }
}
