<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class UserController extends Controller {
    public function index(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // Verificación de rol, solo permite acceso a usuarios con rol 1 o 2
        if (session('role') === '1' || session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // URL de la API de usuarios
        $apiUrl = $baseApiUrl . '/api/users';
        $apiSearchUrl = $baseApiUrl . '/api/searchUsers';
        $searchQuery = $request->input('query');

        // Parámetros de paginación
        $page = $request->input('page', 1); // Página actual, por defecto es 1
        $perPage = 100; // Número máximo de elementos por página

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
                $users = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $users = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }

            // Pasa los datos de usuarios y los parámetros de paginación a la vista y renderiza la vista
            return view('users.index', compact('users', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        }

        // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
        return redirect()->back()->with('error', 'Error al obtener los usuarios de la API');
    }


    public function create() {
        // Verificación de rol, solo permite acceso a usuarios con rol 1 o 2
        if (session('role') === '1' || session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        return view('users.create');
    }


    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'admin_password' => 'required|string'
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu API para registrar usuarios
        $apiUrl = $baseApiUrl . '/api/register';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
        try {
            $response = Http::withToken($token)->post($apiUrl, $validatedData);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                // Redirigir a una página de éxito o mostrar un mensaje de éxito
                return redirect()->route('users.index')->with('success', 'Usuario registrado exitosamente.');
            } else {
                // Manejar errores si la solicitud no fue exitosa
                $errorMessage = $response->json()['message'] ?? 'Error al registrar el usuario. Por favor, Contraseña Incorrecta.';
                return back()->withInput()->withErrors($errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al registrar el usuario: ' . $e->getMessage());
        }
    }


    public function edit($id, Request $request) {
        // Verificación de rol, solo permite acceso a usuarios con rol 1 o 2
        if (session('role') === '1' || session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // // Verificación de que el user id no sea 1
        // if ($id == 1) {
        //     return redirect()->back()->with('error', 'No puedes editar a este usuario');
        // }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un usuario específico
        $apiUrl = $baseApiUrl . '/api/users/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP GET a tu API para obtener los datos del usuario
        $response = Http::withToken($token)->get($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodificar la respuesta JSON en un array asociativo
            $user = $response->json();

            // Pasar los datos del usuario a la vista y renderizar la vista de edición
            return view('users.edit', compact('user'));
        } else {
            // Manejo de error si la solicitud no fue exitosa
            return redirect()->back()->with('error', 'No se pudo obtener la información del usuario');
        }
    }




    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'role' => 'required|string',
            'admin_password' => 'required|string',
        ]);
    
        // Configurar los datos del formulario
        $formParams = [
            [
                'name' => '_method',
                'contents' => 'PUT',
            ],
            [
                'name' => 'name',
                'contents' => $validatedData['name'],
            ],
            [
                'name' => 'email',
                'contents' => $validatedData['email'],
            ],
            [
                'name' => 'role',
                'contents' => $validatedData['role'],
            ],
            [
                'name' => 'admin_password',
                'contents' => $validatedData['admin_password'],
            ],
        ];
    
        // Si se proporciona una nueva contraseña, agregarla antes de actualizar
        if ($request->filled('password')) {
            $formParams[] = [
                'name' => 'password',
                'contents' => $request->password,
            ];
        }
    
        // URL base de la API
        $baseApiUrl = config('app.backend_api');
    
        // URL de la API para actualizar un usuario específico
        $apiUrl = $baseApiUrl . '/api/users/' . $id;
    
        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // Crear un cliente Guzzle
        $client = new \GuzzleHttp\Client();
    
        // Realizar una solicitud HTTP POST con _method=PUT para actualizar el usuario
        try {
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'multipart' => $formParams,
            ]);
    
            // Verificar si la solicitud fue exitosa
            if ($response->getStatusCode() == 200) {
                // Redirigir a una página de éxito o mostrar un mensaje de éxito
                return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
            } else {
                // Manejar errores si la solicitud no fue exitosa
                return back()->withInput()->withErrors('Error al actualizar el usuario. Por favor, inténtalo de nuevo más tarde.');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
    
            if (isset($responseBody['message']) && $responseBody['message'] == 'El correo electrónico ya está registrado') {
                return back()->withInput()->withErrors(['email' => 'El correo electrónico ya está registrado']);
            } elseif (isset($responseBody['message']) && $responseBody['message'] == 'Contraseña de administrador incorrecta') {
                return back()->withInput()->withErrors(['admin_password' => 'Contraseña de administrador incorrecta']);
            } else {
                return back()->withInput()->withErrors('Error al actualizar el usuario. Por favor, inténtalo de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el usuario: ' . $e->getMessage());
        }
    }
    
    public function destroy(Request $request, $id) {
        // Validar que se proporcione la contraseña del administrador
        $validatedData = $request->validate([
            'admin_password' => 'required|string',
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar un usuario específico
        $apiUrl = $baseApiUrl . '/api/users/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Crear un cliente Guzzle
        $client = new Client();

        // Configurar los datos del formulario
        $formParams = [
            [
                'name' => '_method',
                'contents' => 'DELETE',
            ],
            [
                'name' => 'admin_password',
                'contents' => $validatedData['admin_password'],
            ],
        ];

        // Realizar una solicitud HTTP POST con _method=DELETE para eliminar el usuario
        try {
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'multipart' => $formParams,
            ]);

            // Verificar si la solicitud fue exitosa
            if ($response->getStatusCode() == 200) {
                // Redirigir a una página de éxito o mostrar un mensaje de éxito
                return response()->json(['message' => 'Usuario eliminado con éxito']);
            } else {
                // Manejar errores si la solicitud no fue exitosa
                return response()->json(['message' => 'Error al eliminar el usuario. Por favor, inténtalo de nuevo más tarde.'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario: ' . 'Contraseña Incorrecta'], 500);
        }
    }
}
