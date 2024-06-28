<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function index(Request $request)
    {
        $baseApiUrl = config('app.backend_api');
        $apibakcup = $baseApiUrl . '/api/export-database';
        $apiImport = $baseApiUrl . '/api/import-database';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');
        return view('database.index', compact('apibakcup', 'token', 'apiImport'));
    }
    
}    