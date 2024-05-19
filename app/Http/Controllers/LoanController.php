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

    public function edit($id, Request $request) {
        // URL de la API para obtener un préstamo específico
        $apiUrl = 'http://127.0.0.1:8000/api/loans/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API para obtener los datos del préstamo
        $response = Http::withToken($token)->get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $loan = $response->json();

            // Muestra el formulario de edición con los datos del préstamo
            return view('loans.edit', compact('loan'));
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al obtener los datos del préstamo. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function update(Request $request, $id) {
        // URL de la API para actualizar el préstamo
        $apiUrl = 'http://127.0.0.1:8000/api/comeBackLoan/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP PUT a la API
        $response = Http::withToken($token)->put($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Devuelve la respuesta JSON
            return $response->json();
        } else {
            // Devuelve un mensaje de error si la solicitud no fue exitosa
            return response()->json(['error' => 'Error al devolver el préstamo.'], $response->status());
        }
    }

    public function destroy($id, Request $request) {
        // URL de la API para eliminar un préstamo específico
        $apiUrl = 'http://127.0.0.1:8000/api/loans/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP DELETE a la API para eliminar el préstamo
        $response = Http::withToken($token)->delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('loans.index')->with('success', 'Préstamo eliminado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            $errorMessage = $response->json()['message'] ?? 'Error al eliminar el préstamo. Por favor, inténtalo de nuevo más tarde.';
            return redirect()->route('loans.index')->with('error', $errorMessage);
        }
    }
}
