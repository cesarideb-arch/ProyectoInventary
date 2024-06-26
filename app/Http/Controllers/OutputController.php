<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OutputController extends Controller {
    public function index(Request $request) {
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/outputs';
        $apiSearchUrl = $baseApiUrl . '/api/searchOutput';
        $apiGetCountMonthOutputUrl = $baseApiUrl . '/api/GetCountMonthOutput';
        $apiGetOutputsCountMonthNumber = $baseApiUrl . '/api/GetOutputsCountMonthNumber';
        $apiPostBetweenOutput = $baseApiUrl . '/api/PostBetweenOutput';
    
        $searchQuery = $request->input('query');
        $page = $request->input('page', 1);
        $perPage = 10;
        $token = $request->session()->get('token');
    
        if ($searchQuery) {
            $apiSearchUrl .= '?search=' . urlencode($searchQuery) . '&page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiSearchUrl);
        } else {
            $apiUrl .= '?page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiUrl);
        }
    
        if ($response->successful()) {
            $data = $response->json();
            if (is_array($data) && array_key_exists('data', $data)) {
                $outputs = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                $outputs = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }
    
            $monthCountResponse = Http::withToken($token)->get($apiGetOutputsCountMonthNumber);
            $monthData = $monthCountResponse->successful() ? $monthCountResponse->json() : ['count' => 0];
    
            if ($request->has('download')) {
                $downloadType = $request->input('download');
    
                if ($downloadType === 'pdf') {
                    $htmlContent = view('outputs.pdf', compact('outputs'))->render();
                    $htmlFilePath = storage_path('temp/outputs_temp_file.html');
                    file_put_contents($htmlFilePath, $htmlContent);
    
                    if (!file_exists($htmlFilePath)) {
                        return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                    }
    
                    $pdfFilePath = storage_path('temp/Salidas.pdf');
                    $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';
    
                    exec($command, $output, $returnVar);
    
                    if ($returnVar === 0) {
                        return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                    } else {
                        return redirect()->back()->with('error', 'Error al generar el PDF');
                    }
                } elseif ($downloadType === 'month_pdf') {
                    $monthResponse = Http::withToken($token)->get($apiGetCountMonthOutputUrl);
    
                    if ($monthResponse->successful()) {
                        $monthData = $monthResponse->json();
    
                        $htmlContent = view('outputs.month_pdf', compact('monthData'))->render();
                        $htmlFilePath = storage_path('temp/outputs_month_temp_file.html');
                        file_put_contents($htmlFilePath, $htmlContent);
    
                        if (!file_exists($htmlFilePath)) {
                            return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                        }
    
                        $pdfFilePath = storage_path('temp/Salidas_Mes.pdf');
                        $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';
    
                        exec($command, $output, $returnVar);
    
                        if ($returnVar === 0) {
                            return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                        } else {
                            return redirect()->back()->with('error', 'Error al generar el PDF');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Error al obtener las salidas del mes de la API');
                    }
                } elseif ($downloadType === 'between_dates_pdf') {
                    $start_date = $request->input('start_date');
                    $end_date = $request->input('end_date');
    
                    $dateRangeResponse = Http::withToken($token)->post($apiPostBetweenOutput, [
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ]);
    
                    if ($dateRangeResponse->successful()) {
                        $dateRangeData = $dateRangeResponse->json();
    
                        $htmlContent = view('outputs.between_dates_pdf', compact('dateRangeData'))->render();
                        $htmlFilePath = storage_path('temp/outputs_between_dates_temp_file.html');
                        file_put_contents($htmlFilePath, $htmlContent);
    
                        if (!file_exists($htmlFilePath)) {
                            return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                        }
    
                        $pdfFilePath = storage_path('temp/Salidas_Rango_Seleccionado.pdf');
                        $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';
    
                        exec($command, $output, $returnVar);
    
                        if ($returnVar === 0) {
                            return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                        } else {
                            return redirect()->back()->with('error', 'Error al generar el PDF');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Error al obtener las salidas del rango de fechas de la API');
                    }
                }
            }
    
            return view('outputs.index', compact('outputs', 'searchQuery', 'total', 'currentPage', 'lastPage', 'monthData'));
        }
    
        return redirect()->back()->with('error', 'Error al obtener las salidas de la API');
    }
}    