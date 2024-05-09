<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupplierController extends Controller
{
    public function index()
{
    // URL de la nueva API de proveedores
    $apiUrl = 'http://127.0.0.1:8000/api/suppliers';

    // Realiza una solicitud HTTP GET a la API y obtén la respuesta
    $response = Http::get($apiUrl);

    // Verifica si la solicitud fue exitosa
    if ($response->successful()) {
        // Decodifica la respuesta JSON en un array asociativo
        $suppliers = $response->json();

        // Pasa los datos de proveedores a la vista y renderiza la vista
        return view('suppliers.index', compact('suppliers'));
    }
}
}
