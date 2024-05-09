<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller {
    public function index() {
        // URL de la API de proveedores
        $apiUrl = 'http://127.0.0.1:8000/api/projects';

        // Realiza una solicitud HTTP GET a la API y obtén la respuesta
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $projects = $response->json();

            // Pasa los datos de proveedores a la vista y renderiza la vista
            return view('projects.index', compact('projects'));
        }
    }


    public function create() {
        return view('projects.create');
    }


    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'company_name' => 'required|string|max:50',
            'rfc' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:50',
            'email' => 'required|string|max:50|email',
            'client_name' => 'required|string|max:100',
            // Agregar otras validaciones si es necesario
        ]);

        // URL de la API para almacenar proyectos
        $apiUrl = 'http://localhost:8000/api/projects';

        // Realizar una solicitud HTTP POST a la API con los datos validados del formulario
        $response = Http::post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el proyecto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id) {
        // URL de la API para obtener un proyecto específico
        $apiUrl = 'http://localhost:8000/api/projects/' . $id;

        // Realiza una solicitud HTTP GET a la API para obtener los datos del proyecto
        $response = Http::get($apiUrl);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $project = $response->json();

            // Muestra el formulario de edición con los datos del proyecto
            return view('projects.edit', compact('project'));
        }
    }

    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'string|max:100',
            'description' => 'nullable|string',
            'company_name' => 'string|max:50',
            'rfc' => 'string|max:50',
            'address' => 'string|max:100',
            'phone_number' => 'string|max:50',
            'email' => 'string|max:50|email',
            'client_name' => 'string|max:100',
            // Agregar otras validaciones si es necesario
        ]);

        // URL de la API para actualizar un proyecto específico
        $apiUrl = 'http://localhost:8000/api/projects/' . $id;

        // Realizar una solicitud HTTP PUT para actualizar el proyecto
        $response = Http::put($apiUrl, $request->all());

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto actualizado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al actualizar el proyecto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function destroy($id)
    {
        // URL de la API para eliminar un proyecto específico
        $apiUrl = 'http://localhost:8000/api/projects/' . $id;

        // Realizar una solicitud HTTP DELETE a la API para eliminar el proyecto
        $response = Http::delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto eliminado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withErrors('Error al eliminar el proyecto. Por favor, inténtalo de nuevo más tarde.');
        }
    }
}
