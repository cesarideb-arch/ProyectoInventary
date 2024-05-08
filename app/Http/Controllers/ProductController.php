<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{

        public function index()
        {
            // URL de la API de productos
            $apiUrl = 'http://127.0.0.1:8000/api/products';
        
            // Realiza una solicitud HTTP GET a la API y obtén la respuesta
            $response = Http::get($apiUrl);
        
            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {
                // Decodifica la respuesta JSON en un array asociativo
                $products = $response->json();
        
                // Pasa los datos de productos a la vista y renderiza la vista
                return view('products.index', compact('products'));
        }
}

public function create()
{
    return view('products.create');

}


public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // URL de la API de productos
        $apiUrl = 'http://127.0.0.1:8000/api/products';

        // Realizar una solicitud HTTP POST a la API con los datos del formulario
        $response = Http::post($apiUrl, [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Obtener la respuesta de la API
            $responseData = $response->json();

            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
        } 

    }

    public function edit($id)
    {
        // URL de la API para obtener un producto específico
        $apiUrl = 'http://127.0.0.1:8000/api/products/' . $id;
    
        // Realiza una solicitud HTTP GET a la API para obtener los datos del producto
        $response = Http::get($apiUrl);
    
        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $product = $response->json();
    
            // Muestra el formulario de edición con los datos del producto
            return view('products.edit', compact('product'));
        } 
}
public function update(Request $request, $id)
{
    // Validar los datos de la solicitud
    $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
    ]);

    // URL de la API para actualizar un producto específico
    $apiUrl = 'http://127.0.0.1:8000/api/products/' . $id;

    // Realizar una solicitud HTTP PUT a la API con los datos del formulario
    $response = Http::put($apiUrl, [
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
    ]);

    // Verificar si la solicitud fue exitosa
    if ($response->successful()) {
        // Obtener la respuesta de la API
        $responseData = $response->json();

        // Redirigir a una página de éxito o mostrar un mensaje de éxito
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    } else {
        // Si la solicitud no fue exitosa, redirigir de vuelta con un mensaje de error
        return redirect()->back()->with('error', 'No se pudo actualizar el producto. Por favor, inténtalo de nuevo.');
    }
}

public function destroy($id)
{
    // URL de la API para eliminar un producto específico
    $apiUrl = 'http://127.0.0.1:8000/api/products/' . $id;

    // Realizar una solicitud HTTP DELETE a la API
    $response = Http::delete($apiUrl);

    // Verificar si la solicitud fue exitosa
    if ($response->successful()) {
        // Redirigir a una página de éxito o mostrar un mensaje de éxito
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    } else {
        // Si la solicitud no fue exitosa, redirigir de vuelta con un mensaje de error
        return redirect()->back()->with('error', 'No se pudo eliminar el producto. Por favor, inténtalo de nuevo.');
    }
}



}