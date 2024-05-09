<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupplierController extends Controller {
    public function index() {
        // URL de la API de proveedores
        $apiUrl = 'http://127.0.0.1:8000/api/suppliers';

        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $suppliers = $response->json();

            // Pasa los datos de proveedores a la vista y renderiza la vista
            return view('suppliers.index', compact('suppliers'));
        }
    }

    public function create() {
        return view('suppliers.create');
    }

    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'article' => 'required|string|max:255',
            'price' => 'required|numeric|between:0,999999.99',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:100',
            // Otros campos de proveedor que puedan existir en tu API
        ]);

        // URL de tu API para almacenar proveedores
        $apiUrl = 'http://localhost:8000/api/suppliers';

        // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
        $response = Http::post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('suppliers.index')->with('success', 'Proveedor creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el proveedor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id) {
        // URL de la API para obtener un proveedor específico
        $apiUrl = 'http://127.0.0.1:8000/api/suppliers/' . $id;

        // Realiza una solicitud HTTP GET a la API para obtener los datos del proveedor
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $supplier = $response->json();

            // Muestra el formulario de edición con los datos del proveedor
            return view('suppliers.edit', compact('supplier'));
        }
    }


    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $request->validate([
            'article' => 'string|max:255',
            'price' => 'numeric',
            'company' => 'string|max:255',
            'phone' => 'string|max:255',
            'email' => 'email|max:255',
            'address' => 'string|max:100' // Nueva validación para el campo address
        ]);

        // URL de la API para actualizar un proveedor específico
        $apiUrl = 'http://127.0.0.1:8000/api/suppliers/' . $id;

        // Realizar una solicitud HTTP PUT para actualizar el proveedor
        $response = Http::put($apiUrl, $request->all());

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al actualizar el proveedor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function destroy($id) {
        // URL de la API para eliminar un proveedor específico
        $apiUrl = 'http://127.0.0.1:8000/api/suppliers/' . $id;

        // Realizar una solicitud HTTP DELETE a la API para eliminar el proveedor
        $response = Http::delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al eliminar el proveedor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    
}
