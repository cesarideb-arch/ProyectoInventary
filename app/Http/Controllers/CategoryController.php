<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Verificación de rol, solo permite acceso a usuarios con rol distinto de 2
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Verificar si hay token
        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada. Por favor, inicia sesión nuevamente.');
        }

        // Parámetros de paginación
        $page = $request->input('page', 1);
        $perPage = 100;
        $searchQuery = $request->input('query');

        try {
            // Si hay un término de búsqueda, usar la URL de búsqueda
            if ($searchQuery) {
                $apiUrl = $baseApiUrl . '/api/searchCategory';
                $response = Http::withToken($token)
                    ->withOptions(['verify' => false])
                    ->get($apiUrl, [
                        'search' => $searchQuery,
                        'page' => $page,
                        'per_page' => $perPage
                    ]);
            } else {
                $apiUrl = $baseApiUrl . '/api/categories';
                $response = Http::withToken($token)
                    ->withOptions(['verify' => false])
                    ->get($apiUrl, [
                        'page' => $page,
                        'per_page' => $perPage
                    ]);
            }

            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                
                // Verifica la estructura de la respuesta
                if (isset($data['data']) && is_array($data['data'])) {
                    $categories = $data['data'];
                    $total = $data['total'] ?? 0;
                    $currentPage = $data['current_page'] ?? 1;
                    $lastPage = $data['last_page'] ?? 1;
                } else {
                    // Si la estructura es diferente, adaptarse
                    $categories = $data;
                    $total = count($categories);
                    $currentPage = $page;
                    $lastPage = ceil($total / $perPage);
                    
                    // Aplicar paginación manual si es necesario
                    $categories = array_slice($categories, ($page - 1) * $perPage, $perPage);
                }

                return view('categories.index', compact('categories', 'searchQuery', 'total', 'currentPage', 'lastPage'));
            } else {
                $errorMessage = 'Error al cargar las categorías.';
                if ($response->status() === 401) {
                    return redirect()->route('login')->with('error', 'Sesión expirada.');
                } else if ($response->status() === 404) {
                    $errorMessage = 'No se encontraron categorías.';
                }
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@index: ' . $e->getMessage());
            return back()->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function create()
    {
        // Verificación de rol, solo permite acceso a usuarios con rol 1 o 0
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Verificación de rol
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción');
        }

        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'materials' => 'nullable|string|max:500'
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/categories';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        try {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->timeout(30)
                ->post($apiUrl, $validatedData);

            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Error al crear la categoría.';
                
                if ($response->status() === 422) {
                    // Errores de validación del API
                    $errors = $errorData['errors'] ?? [];
                    return back()->withInput()->withErrors($errors);
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function edit($id, Request $request)
    {
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        try {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->get($apiUrl);

            if ($response->successful()) {
                $category = $response->json();
                
                // Verificar si la categoría existe
                if (empty($category)) {
                    return redirect()->route('categories.index')->with('error', 'Categoría no encontrada.');
                }
                
                return view('categories.edit', compact('category'));
            } else {
                if ($response->status() === 404) {
                    return redirect()->route('categories.index')->with('error', 'Categoría no encontrada.');
                }
                return back()->with('error', 'Error al obtener los datos de la categoría.');
            }
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@edit: ' . $e->getMessage());
            return back()->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function update(Request $request, $id)
    {
        // Verificación de rol
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción');
        }

        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'materials' => 'nullable|string|max:500'
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        try {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->timeout(30)
                ->put($apiUrl, $validatedData);

            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Error al actualizar la categoría.';
                
                if ($response->status() === 422) {
                    $errors = $errorData['errors'] ?? [];
                    return back()->withInput()->withErrors($errors);
                }
                
                if ($response->status() === 404) {
                    return redirect()->route('categories.index')->with('error', 'Categoría no encontrada.');
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@update: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function destroy($id, Request $request)
    {
        // Verificación de rol
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción');
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/categories/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        try {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->timeout(30)
                ->delete($apiUrl);

            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Error al eliminar la categoría.';
                
                if ($response->status() === 404) {
                    return redirect()->route('categories.index')->with('error', 'Categoría no encontrada.');
                }
                
                // Si la categoría tiene productos asociados
                if ($response->status() === 409) {
                    $errorMessage = 'No se puede eliminar la categoría porque tiene productos asociados.';
                }
                
                return redirect()->route('categories.index')->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@destroy: ' . $e->getMessage());
            return redirect()->route('categories.index')->with('error', 'Error de conexión con el servidor.');
        }
    }
}