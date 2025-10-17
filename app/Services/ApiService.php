<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('app.backend_api');
        $this->token = Session::get('token');
    }

    public function get($endpoint)
    {
        return Http::withToken($this->token)
                  ->withOptions(['verify' => false])
                  ->get($this->baseUrl . $endpoint);
    }

    public function post($endpoint, $data = [])
    {
        return Http::withToken($this->token)
                  ->withOptions(['verify' => false])
                  ->post($this->baseUrl . $endpoint, $data);
    }

    public function put($endpoint, $data = [])
    {
        return Http::withToken($this->token)
                  ->withOptions(['verify' => false])
                  ->put($this->baseUrl . $endpoint, $data);
    }

    public function delete($endpoint)
    {
        return Http::withToken($this->token)
                  ->withOptions(['verify' => false])
                  ->delete($this->baseUrl . $endpoint);
    }

    public function withToken($token)
    {
        $this->token = $token;
        return $this;
    }
}