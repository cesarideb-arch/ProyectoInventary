<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller {

    public function index() {
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

    public function create() {

        $apiUrl = 'http://127.0.0.1:8000/api/getCategoryProducts';

        $response = Http::get($apiUrl)->json();

        $suppliers = $response['suppliers'];

        $categories = $response['categories'];

        return view('products.create', compact('suppliers', 'categories'));
    }


    public function store(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'model' => 'nullable|string|max:50',
            'measurement_unit' => 'nullable|string|max:15',
            'brand' => 'nullable|string|max:50',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'price' => 'required|numeric|between:0,999999.99',
            'profile_image' => 'nullable|file|max:2048|mimes:jpeg,png,gif,svg',
            'serie' => 'nullable|string|max:40',
            'observations' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:20',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
        ]);

        // URL de tu API para almacenar productos
        $apiUrl = 'http://localhost:8000/api/products';

        // dd($request->hasFile('profile_image'));

        // Verificar si la solicitud contiene una imagen
        /*  if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageContents = file_get_contents($file->getPathname());
            $imageName = $file->getClientOriginalName();

            // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
            $response = Http::attach(
                'profile_image',
                $imageContents,
                $imageName
            )->post($apiUrl, $validatedData);

        } else {
            // Si no hay imagen adjunta, simplemente envía los datos sin el campo de imagen
            $response = Http::post($apiUrl, $validatedData);
        } */

        $response = Http::attach(
            'profile_image',
            file_get_contents($request->file('profile_image')),
            $request->file('profile_image')->getClientOriginalName()
        )->post($apiUrl, $validatedData);

        dd($response->json());

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el producto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id) {
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

    public function update(Request $request, $id) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'model' => 'nullable|string|max:50',
            'measurement_unit' => 'nullable|string|max:15',
            'brand' => 'nullable|string|max:50',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'price' => 'required|numeric|between:0,999999.99',
            'profile_image' => 'nullable|file|max:2048|mimes:jpeg,png,gif,svg',
            'serie' => 'nullable|string|max:40',
            'observations' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:20',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
        ]);

        // URL de tu API para actualizar productos
        $apiUrl = 'http://localhost:8000/api/products/' . $id;
        //  dd($request->all());
        // Verificar si la solicitud contiene una imagen
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageContents = file_get_contents($file->getPathname());
            $imageName = $file->getClientOriginalName();



            $validatedData['_method'] = 'PUT';
            // Realizar una solicitud HTTP PUT a tu API con los datos validados del formulario
            $response = Http::attach(
                'profile_image',
                $imageContents,
                $imageName
            )->post($apiUrl, $validatedData);

            // dd($response->json());
        } else {
            // Si no hay imagen adjunta, simplemente envía los datos sin el campo de imagen
            $response = Http::post($apiUrl, $validatedData);
        }

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al actualizar el producto. Por favor, inténtalo de nuevo más tarde.');
        }
    }


    public function destroy($id) {
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
