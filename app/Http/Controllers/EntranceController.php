<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class EntranceController extends Controller {
    public function index(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');
    
        // URL de la API de entradas
        $apiUrl = $baseApiUrl . '/api/entrances';
        $apiSearchUrl = $baseApiUrl . '/api/searchEntrance';
        $searchQuery = $request->input('query');
    
        // Parámetros de paginación
        $page = $request->input('page', 1); // Página actual, por defecto es 1
        $perPage = 15; // Número máximo de elementos por página
    
        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
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
                $entrances = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                // Asume que toda la respuesta es el conjunto de datos
                $entrances = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }
    
            // Si el parámetro 'download' está presente y es 'pdf', generar el PDF
            if ($request->has('download') && $request->input('download') === 'pdf') {
                // Guardar HTML en un archivo temporal en una ubicación accesible
                $htmlContent = view('entrances.pdf', compact('entrances'))->render();
                $htmlFilePath = storage_path('temp/entrances_temp_file.html');
                file_put_contents($htmlFilePath, $htmlContent);
    
                // Verificar si el archivo HTML se genera correctamente
                if (!file_exists($htmlFilePath)) {
                    return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                }
    
                // Definir la ruta de salida del PDF
                $pdfFilePath = storage_path('temp/Entradas.pdf');
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
    
            // Pasa los datos de entradas y la página actual a la vista y renderiza la vista
            return view('entrances.index', compact('entrances', 'page', 'total', 'currentPage', 'lastPage'));
        } else {
            // Si la solicitud falla, muestra un mensaje de error
            return 'Error: ' . $response->status();
        }
    }
}    