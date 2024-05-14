<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller {
    public function index() {
        // URL de la API de proveedores
        $apiUrl = 'http://127.0.0.1:8000/api/categories';

        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $categories = $response->json();

            // Pasa los datos de proveedores a la vista y renderiza la vista
            return view('categories.index', compact('categories'));
        }
    }


    public function create() {
        return view('categories.create');
    }


    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100'
        ]);

        // URL de tu API para almacenar categorías
        $apiUrl = 'http://localhost:8000/api/categories';

        // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
        $response = Http::post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id) {
        // URL de la API para obtener una categoría específica
        $apiUrl = 'http://localhost:8000/api/categories/' . $id;

        // Realizar una solicitud HTTP GET a la API para obtener los datos de la categoría
        $response = Http::get($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodificar la respuesta JSON en un array asociativo
            $category = $response->json();

            // Mostrar el formulario de edición con los datos de la categoría
            return view('categories.edit', compact('category'));
        }
    }



    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'string|max:100',
            'description' => 'nullable|string|max:100'
            // Agrega aquí otras validaciones si es necesario
        ]);

        // URL de la API para actualizar una categoría específica
        $apiUrl = 'http://localhost:8000/api/categories/' . $id;

        // Realizar una solicitud HTTP PUT para actualizar la categoría
        $response = Http::put($apiUrl, $request->all());

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al actualizar la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function destroy($id) {
        // URL de la API para eliminar una categoría específica
        $apiUrl = 'http://localhost:8000/api/categories/' . $id;

        // Realizar una solicitud HTTP DELETE a la API para eliminar la categoría
        $response = Http::delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al eliminar la categoría. Por favor, inténtalo de nuevo más tarde.');
        }
    }
}
