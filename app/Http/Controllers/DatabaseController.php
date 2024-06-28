<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function index(Request $request)
    {
        $baseApiUrl = config('app.backend_api');
        $apibakcup = $baseApiUrl . '/api/export-database';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        if (session('role') === '2' || session('role') === '1') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        return view('database.index', compact('apibakcup', 'token'));
    }
    
}    