<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller {

    public function index(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API de categorías y búsqueda
        $apiUrl = $baseApiUrl . '/api/categories';
        $apiSearchUrl = $baseApiUrl . '/api/searchCategory';
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
                $categories = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $categories = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }

            // Pasa los datos de categorías y la página actual a la vista y renderiza la vista
            return view('categories.index', compact('categories', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        } else {
            // Si la solicitud falla, muestra un mensaje de error
            return 'Error: ' . $response->status();
        }
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100'
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu API para almacenar categorías
        $apiUrl = $baseApiUrl . '/api/categories';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener una categoría específica
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP GET a la API para obtener los datos de la categoría
        $response = Http::withToken($token)->get($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodificar la respuesta JSON en un array asociativo
            $category = $response->json();

            // Mostrar el formulario de edición con los datos de la categoría
            return view('categories.edit', compact('category'));
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al obtener los datos de la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100'
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para actualizar una categoría específica
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP PUT para actualizar la categoría
        $response = Http::withToken($token)->put($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al actualizar la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function destroy($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar una categoría específica
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP DELETE a la API para eliminar la categoría
        $response = Http::withToken($token)->delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
        } else {
            $errorMessage = $response->json()['message'] ?? 'Error al eliminar la categoría. Por favor, inténtalo de nuevo más tarde.';
            return redirect()->route('categories.index')->with('error', $errorMessage);
        }
    }
}
