<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller {

    public function index(Request $request) {

        if (session('role') === '2') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página');
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API de préstamos
        $apiUrl = $baseApiUrl . '/api/loans';
        $apiSearchUrl = $baseApiUrl . '/api/searchLoan';
        $apiGetCountMonthLoanUrl = $baseApiUrl . '/api/GetCountMonthLoan';
        $apiGetFinished = $baseApiUrl . '/api/GetFinished';
        $apiGetStarted = $baseApiUrl . '/api/GetStarted';
        $apiGetLoanCountMonthNumberUrl = $baseApiUrl . '/api/GetLoanCountMonthNumber';

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

            // Obtener el conteo de préstamos del mes actual
            $monthLoanResponse = Http::withToken($token)->get($apiGetLoanCountMonthNumberUrl);
            $monthDataNumber = $monthLoanResponse->successful() ? $monthLoanResponse->json() : ['count' => 0];

            // Si el parámetro 'download' está presente, generar el PDF correspondiente
            if ($request->has('download')) {
                $downloadType = $request->input('download');
                $htmlFilePath = storage_path('temp/loans_temp_file.html');
                $pdfFilePath = storage_path('temp/Prestamos.pdf');

                if ($downloadType === 'pdf') {
                    // Generar PDF para todos los préstamos
                    $htmlContent = view('loans.pdf', compact('loans'))->render();
                    file_put_contents($htmlFilePath, $htmlContent);
                } elseif ($downloadType === 'month_pdf') {
                    // Generar PDF para los préstamos del mes actual
                    $monthResponse = Http::withToken($token)->get($apiGetCountMonthLoanUrl);
                    if ($monthResponse->successful()) {
                        $monthData = $monthResponse->json();
                        $htmlContent = view('loans.month_pdf', compact('monthData'))->render();
                        $pdfFilePath = storage_path('temp/Prestamos_Mes.pdf');
                        file_put_contents($htmlFilePath, $htmlContent);
                    } else {
                        return redirect()->back()->with('error', 'Error al obtener los préstamos del mes de la API');
                    }
                } elseif ($downloadType === 'finished_pdf') {
                    // Generar PDF para los préstamos finalizados
                    $finishedResponse = Http::withToken($token)->get($apiGetFinished);
                    if ($finishedResponse->successful()) {
                        $finishedData = $finishedResponse->json();
                        $htmlContent = view('loans.finished_pdf', compact('finishedData'))->render();
                        $pdfFilePath = storage_path('temp/Prestamos_Finalizados.pdf');
                        file_put_contents($htmlFilePath, $htmlContent);
                    } else {
                        return redirect()->back()->with('error', 'Error al obtener los préstamos finalizados de la API');
                    }
                } elseif ($downloadType === 'started_pdf') {
                    // Generar PDF para los préstamos iniciados
                    $startedResponse = Http::withToken($token)->get($apiGetStarted);
                    if ($startedResponse->successful()) {
                        $startedData = $startedResponse->json();
                        $htmlContent = view('loans.started_pdf', compact('startedData'))->render();
                        $pdfFilePath = storage_path('temp/Prestamos_Iniciados.pdf');
                        file_put_contents($htmlFilePath, $htmlContent);
                    } else {
                        return redirect()->back()->with('error', 'Error al obtener los préstamos iniciados de la API');
                    }
                }

                if (!file_exists($htmlFilePath)) {
                    return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                }

                $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';

                exec($command, $output, $returnVar);

                if ($returnVar === 0) {
                    return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                } else {
                    return redirect()->back()->with('error', 'Error al generar el PDF');
                }
            }

            // Pasa los datos de préstamos y los parámetros de paginación a la vista y renderiza la vista
            return view('loans.index', compact('loans', 'searchQuery', 'total', 'currentPage', 'lastPage', 'monthDataNumber'));
        }

        // Si la solicitud no fue exitosa, redirige o muestra un mensaje de error
        return redirect()->back()->with('error', 'Error al obtener los préstamos de la API');
    }

    public function edit($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un préstamo específico
        $apiUrl = $baseApiUrl . '/api/loans/' . $id;

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
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/comeBackLoan/' . $id;
        $token = $request->session()->get('token');

        // Incluye las observaciones en la solicitud
        $response = Http::withToken($token)->put($apiUrl, [
            'observations' => $request->input('observations')
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Error al devolver el préstamo.'], $response->status());
        }
    }


    public function destroy($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar un préstamo específico
        $apiUrl = $baseApiUrl . '/api/loans/' . $id;

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
