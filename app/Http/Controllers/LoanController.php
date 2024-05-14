<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller {
    public function index() {
        // URL de la API de préstamos
        $apiUrl = 'http://127.0.0.1:8000/api/loans';

        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $loans = $response->json();

            // Pasa los datos de préstamos a la vista y renderiza la vista
            return view('loans.index', compact('loans'));
        }
    }






    public function edit($id) {



    }


    // public function update(Request $request, $id) {
    //     // URL de la API para actualizar el préstamo
    //     $apiUrl = 'http://127.0.0.1:8000/api/comeBackLoan/' . $id;

    //     // Realiza una solicitud HTTP PUT a la API
    //     $response = Http::put($apiUrl);

    //     // Verifica si la solicitud fue exitosa
    //     if ($response->successful()) {
    //         // Devuelve la respuesta JSON
    //         return $response->json();
    //     } else {
    //         // Devuelve un mensaje de error si la solicitud no fue exitosa
    //         return response()->json(['error' => 'Error al devolver el préstamo.'], $response->status());
    //     }

    //     // Pasa los datos de préstamos a la vista y renderiza la vista
    //     return view('loans.index', compact('loans'));
    // }



    public function update(Request $request, $id) {
        // URL de la API para actualizar el préstamo
        $apiUrl = 'http://127.0.0.1:8000/api/comeBackLoan/' . $id;

        // Realiza una solicitud HTTP PUT a la API
        $response = Http::put($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Devuelve la respuesta JSON
            return $response->json();
        } else {
            // Devuelve un mensaje de error si la solicitud no fue exitosa
            return response()->json(['error' => 'Error al devolver el préstamo.'], $response->status());
        }

        // Pasa los datos de préstamos a la vista y renderiza la vista
        return view('loans.index', compact('loans'));
    }
}
