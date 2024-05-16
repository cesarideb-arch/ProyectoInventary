<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller {
    public function index(Request $request) {
        // URL base de la API de préstamos
        $apiUrl = 'http://127.0.0.1:8000/api/loans';
        $apiSearchUrl = 'http://127.0.0.1:8000/api/searchLoan';
        $searchQuery = $request->input('query');
    
        // Parámetros de paginación
        $page = $request->input('page', 1); // Página actual, por defecto es 1
        $perPage = 15; // Número máximo de elementos por página
    
        // Si hay un término de búsqueda, usar la URL de búsqueda
        if ($searchQuery) {
            $apiSearchUrl .= '?search=' . urlencode($searchQuery) . '&page=' . $page . '&per_page=' . $perPage;
            $response = Http::get($apiSearchUrl);
        } else {
            $apiUrl .= '?page=' . $page . '&per_page=' . $perPage;
            $response = Http::get($apiUrl);
        }
    
        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $data = $response->json();
    
            // Verifica si la clave 'data' está presente en la respuesta
            if (is_array($data) && array_key_exists('data', $data)) {
                $loans = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $loans = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }
    
            // Pasa los datos de préstamos y los parámetros de paginación a la vista y renderiza la vista
            return view('loans.index', compact('loans', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        }
    
        // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
        return redirect()->back()->with('error', 'Error al obtener los préstamos de la API');
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
