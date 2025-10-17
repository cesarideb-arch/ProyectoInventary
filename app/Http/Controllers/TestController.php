<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function testConnection()
    {
        Log::info('=== INICIANDO TEST DE CONEXIÓN FRONTEND -> BACKEND ===');
        
        try {
            // Información de configuración PARA DEBUG
            $configInfo = [
                'api_base_url_config' => config('services.api.base_url'),
                'api_base_url_env' => env('API_BASE_URL'),
                'frontend_url' => config('app.url'),
                'timeout' => config('services.api.timeout', 30)
            ];
            
            Log::info('Configuración detectada:', $configInfo);

            // Mostrar en respuesta para debugging
            echo "<!-- DEBUG INFO: " . json_encode($configInfo) . " -->";

            // Intentar conexión con el endpoint de productos
            $testEndpoint = '/products'; // Cambié a un endpoint más común
            
            Log::info("Probando endpoint: {$configInfo['api_base_url_config']}{$testEndpoint}");
            
            $response = $this->apiService->get($testEndpoint);
            
            $result = [
                'configuracion' => $configInfo,
                'endpoint_probado' => $configInfo['api_base_url_config'] . $testEndpoint,
                'respuesta_api' => [
                    'status_code' => $response->status(),
                    'successful' => $response->successful(),
                    'body' => $response->body()
                ],
                'timestamp' => now()->toDateTimeString()
            ];

            Log::info('Resultado del test:', $result);

            if ($response->successful()) {
                return response()->json([
                    'estado' => '✅ CONEXIÓN EXITOSA',
                    'mensaje' => 'El frontend se conectó correctamente al backend',
                    'detalles' => $result
                ]);
            } else {
                return response()->json([
                    'estado' => '❌ ERROR DE CONEXIÓN',
                    'mensaje' => 'El frontend no pudo conectarse al backend',
                    'detalles' => $result
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error en test de conexión: ' . $e->getMessage());
            
            return response()->json([
                'estado' => '💥 EXCEPCIÓN',
                'mensaje' => 'Ocurrió una excepción durante la conexión',
                'error' => $e->getMessage(),
                'tipo_error' => get_class($e),
                'configuracion_actual' => [
                    'services_api_base_url' => config('services.api.base_url'),
                    'env_api_base_url' => env('API_BASE_URL'),
                    'app_url' => config('app.url')
                ]
            ], 500);
        }
    }

    public function testView()
    {
        $configInfo = [
            'services_api_base_url' => config('services.api.base_url'),
            'env_api_base_url' => env('API_BASE_URL'),
            'frontend_url' => config('app.url'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug')
        ];

        // Debug directo
        echo "<!-- DEBUG CONFIG: " . json_encode($configInfo) . " -->";

        return view('test', compact('configInfo'));
    }
}