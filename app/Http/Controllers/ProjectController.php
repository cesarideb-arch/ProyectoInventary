<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller {

    public function index(Request $request) {
        // Verificación de rol, solo permite acceso a usuarios con rol distinto de 2
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
    
        // URL base de la API
        $baseApiUrl = config('app.backend_api');
    
        // URL de la API de proyectos
        $apiUrl = $baseApiUrl . '/api/projects';
        $apiSearchUrl = $baseApiUrl . '/api/searchProject';
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
                $projects = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $projects = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }
    
            // Si el parámetro 'download' está presente y es 'pdf', generar el PDF
            if ($request->has('download') && $request->input('download') === 'pdf') {
                // Guardar HTML en un archivo temporal en una ubicación accesible
                $htmlContent = view('projects.pdf', compact('projects'))->render();
                $htmlFilePath = storage_path('temp/projects_temp_file.html');
                file_put_contents($htmlFilePath, $htmlContent);
    
                // Verificar si el archivo HTML se genera correctamente
                if (!file_exists($htmlFilePath)) {
                    return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                }
    
                // Definir la ruta de salida del PDF
                $pdfFilePath = storage_path('temp/Proyectos.pdf');
                $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';
    
                // Ejecutar el comando
                exec($command, $output, $returnVar);
    
                // Verificar si el PDF se generó correctamente
                if ($returnVar === 0) {
                    return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                } else {
                    return redirect()->back()->with('error', 'Error al generar el PDF');
                }
            }
    
            // Pasa los datos de proyectos y los parámetros de paginación a la vista y renderiza la vista
            return view('projects.index', compact('projects', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        }
    
        // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
        return redirect()->back()->with('error', 'Error al obtener los proyectos de la API');
    }
    

    public function create() {
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        return view('projects.create');
    }

    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'company_name' => 'required|string|max:50',
            'rfc' => 'nullable|string',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:50',
            'email' => 'required|string|max:50|email',
            'client_name' => 'required|string|max:100',
            // Agregar otras validaciones si es necesario
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para almacenar proyectos
        $apiUrl = $baseApiUrl . '/api/projects';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a la API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el proyecto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id, Request $request) {
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un proyecto específico
        $apiUrl = $baseApiUrl . '/api/projects/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API para obtener los datos del proyecto
        $response = Http::withToken($token)->get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $project = $response->json();

            // Muestra el formulario de edición con los datos del proyecto
            return view('projects.edit', compact('project'));
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al obtener los datos del proyecto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'string|max:100',
            'description' => 'nullable|string',
            'company_name' => 'string|max:50',
            'rfc' => 'nullable|string',
            'address' => 'string|max:100',
            'phone_number' => 'string|max:50',
            'email' => 'string|max:50|email',
            'client_name' => 'string|max:100',
            // Agregar otras validaciones si es necesario
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
                'name' => 'description',
                'contents' => $validatedData['description'] ?? null,
            ],
            [
                'name' => 'company_name',
                'contents' => $validatedData['company_name'],
            ],
            [
                'name' => 'rfc',
                'contents' => $validatedData['rfc'] ?? null,
            ],
            [
                'name' => 'address',
                'contents' => $validatedData['address'],
            ],
            [
                'name' => 'phone_number',
                'contents' => $validatedData['phone_number'],
            ],
            [
                'name' => 'email',
                'contents' => $validatedData['email'],
            ],
            [
                'name' => 'client_name',
                'contents' => $validatedData['client_name'],
            ],
        ];

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para actualizar un proyecto específico
        $apiUrl = $baseApiUrl . '/api/projects/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Crear un cliente Guzzle
        $client = new \GuzzleHttp\Client();

        // Realizar una solicitud HTTP POST con _method=PUT para actualizar el proyecto
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
                return redirect()->route('projects.index')->with('success', 'Proyecto actualizado exitosamente.');
            } else {
                // Manejar errores si la solicitud no fue exitosa
                return back()->withInput()->withErrors('Error al actualizar el proyecto. Por favor, inténtalo de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }


    public function destroy($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar un proyecto específico
        $apiUrl = $baseApiUrl . '/api/projects/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP DELETE a la API para eliminar el proyecto
        $response = Http::withToken($token)->delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto eliminado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            $errorMessage = $response->json()['message'] ?? 'Error al eliminar el proyecto. Por favor, inténtalo de nuevo más tarde.';
            return redirect()->route('projects.index')->with('error', $errorMessage);
        }
    }
}
