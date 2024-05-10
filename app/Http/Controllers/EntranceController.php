<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntranceController extends Controller
{
    public function index() {
        // URL de la API de entradas
        $apiUrl = 'http://127.0.0.1:8000/api/entrances';
    
        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);
    
        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $entrances = $response->json();
    
            // Pasa los datos de entradas a la vista y renderiza la vista
            return view('entrances.index', compact('entrances'));
        }
    }
}
