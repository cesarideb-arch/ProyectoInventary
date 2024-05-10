<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OutputController extends Controller
{
    public function index() {
        // URL de la API de salidas
        $apiUrl = 'http://127.0.0.1:8000/api/outputs';
    
        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);
    
        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $outputs = $response->json();
    
            // Pasa los datos de salidas a la vista y renderiza la vista
            return view('outputs.index', compact('outputs'));
        }
    }
}
