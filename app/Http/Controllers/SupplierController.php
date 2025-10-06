<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\SuppliersExport;

class SupplierController extends Controller {
    
public function index(Request $request)
{
    // URL base de la API
    $baseApiUrl = config('app.backend_api');

    // URL de la API de proveedores
    $apiUrl = $baseApiUrl . '/api/suppliers';
    $apiSearchUrl = $baseApiUrl . '/api/searchSupplier';
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
            $suppliers = $data['data'];
            $total = $data['total'] ?? 0;
            $currentPage = $data['current_page'] ?? 1;
            $lastPage = $data['last_page'] ?? 1;
        } else {
            // Asume que toda la respuesta es el conjunto de datos
            $suppliers = array_slice($data, ($page - 1) * $perPage, $perPage);
            $total = count($data);
            $currentPage = $page;
            $lastPage = ceil($total / $perPage);
        }

        // Si el parámetro 'download' está presente y es 'pdf' o 'excel', generar el archivo correspondiente
        if ($request->has('download')) {
            $downloadType = $request->input('download');
            if ($downloadType === 'pdf') {
                // Guardar HTML en un archivo temporal en una ubicación accesible
                $htmlContent = view('suppliers.pdf', compact('suppliers'))->render();
                $htmlFilePath = storage_path('temp/suppliers_temp_file.html');
                file_put_contents($htmlFilePath, $htmlContent);

                // Verificar si el archivo HTML se genera correctamente
                if (!file_exists($htmlFilePath)) {
                    return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                }

                // Definir la ruta de salida del PDF
                $pdfFilePath = storage_path('temp/Proveedores.pdf');
                $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';

                // Ejecutar el comando
                exec($command, $output, $returnVar);

                // Verificar si el PDF se generó correctamente
                if ($returnVar === 0) {
                    return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                } else {
                    return redirect()->back()->with('error', 'Error al generar el PDF');
                }
            } elseif ($downloadType === 'excel') {
                $filePath = storage_path('temp/Proveedores.xlsx');
                $export = new SuppliersExport($suppliers);
                $export->export($filePath);
                return response()->download($filePath)->deleteFileAfterSend(true);
            }
        }

        // Pasa los datos de proveedores y los parámetros de paginación a la vista y renderiza la vista
        return view('suppliers.index', compact('suppliers', 'searchQuery', 'total', 'currentPage', 'lastPage'));
    }

    // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
    return redirect()->back()->with('error', 'Error al obtener los proveedores de la API');
}

    public function create() {
        // Verificación de rol, solo permite acceso a usuarios con rol distinto de 2
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        return view('suppliers.create');
    }

    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:100',
            // Otros campos de proveedor que puedan existir en tu API
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu API para almacenar proveedores
        $apiUrl = $baseApiUrl . '/api/suppliers';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('suppliers.index')->with('success', 'Proveedor creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el proveedor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id, Request $request) {
        // Verificación de rol, solo permite acceso a usuarios con rol distinto de 2
        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un proveedor específico
        $apiUrl = $baseApiUrl . '/api/suppliers/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API para obtener los datos del proveedor
        $response = Http::withToken($token)->get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $supplier = $response->json();

            // Muestra el formulario de edición con los datos del proveedor
            return view('suppliers.edit', compact('supplier'));
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al obtener los datos del proveedor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
        ]);

        // Configurar los datos del formulario
        $formParams = [
            [
                'name' => '_method',
                'contents' => 'PUT',
            ],
            [
                'name' => 'company',
                'contents' => $validatedData['company'],
            ],
            [
                'name' => 'phone',
                'contents' => $validatedData['phone'],
            ],
            [
                'name' => 'email',
                'contents' => $validatedData['email'] ?? null,
            ],
            [
                'name' => 'address',
                'contents' => $validatedData['address'] ?? null,
            ],
        ];

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para actualizar un proveedor específico
        $apiUrl = $baseApiUrl . '/api/suppliers/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Crear un cliente Guzzle
        $client = new \GuzzleHttp\Client();

        // Realizar una solicitud HTTP POST con _method=PUT para actualizar el proveedor
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
                return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado exitosamente.');
            } else {
                // Manejar errores si la solicitud no fue exitosa
                return back()->withInput()->withErrors('Error al actualizar el proveedor. Por favor, inténtalo de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    public function destroy($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar un proveedor específico
        $apiUrl = $baseApiUrl . '/api/suppliers/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP DELETE a la API para eliminar el proveedor
        $response = Http::withToken($token)->delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            $errorMessage = $response->json()['message'] ?? 'Error al eliminar el proveedor. Por favor, inténtalo de nuevo más tarde.';
            return redirect()->route('suppliers.index')->with('error', $errorMessage);
        }
    }
}
