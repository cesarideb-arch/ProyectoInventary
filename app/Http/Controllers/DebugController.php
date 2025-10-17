<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    public function testConnection()
    {
        $baseApiUrl = config('app.backend_api');
        $token = session('token');
        
        Log::info('Testing connection to: ' . $baseApiUrl);
        Log::info('Session token: ' . ($token ? 'exists' : 'null'));
        
        // Test simple connection
        try {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->timeout(10)
                ->get($baseApiUrl . '/api/users');
                
            Log::info('API Response status: ' . $response->status());
            Log::info('API Response body: ' . $response->body());
            Log::info('API Response headers: ', $response->headers());
            
            return response()->json([
                'status' => $response->status(),
                'success' => $response->successful(),
                'body' => $response->body(),
                'token_exists' => !empty($token),
                'api_url' => $baseApiUrl
            ]);
            
        } catch (\Exception $e) {
            Log::error('Connection test failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'token_exists' => !empty($token),
                'api_url' => $baseApiUrl
            ], 500);
        }
    }
}