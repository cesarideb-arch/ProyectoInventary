<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class ProductController extends Controller {

    public function index(Request $request) {
        // URL base de la API de productos
        $baseApiUrl = config('app.backend_api');
        $apiUrl = $baseApiUrl . '/api/products';
        $searchQuery = $request->input('query');
    
        // Parámetros de paginación
        $page = $request->input('page', 1);
        $perPage = 10;
    
        // Define la URL de búsqueda en la API
        $apiSearchUrl = $baseApiUrl . '/api/search';
    
        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // Si hay una consulta de búsqueda, agrega el parámetro de búsqueda a la URL de la API de búsqueda
        if ($searchQuery) {
            $apiSearchUrl .= '?search=' . urlencode($searchQuery) . '&page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiSearchUrl);
        } else {
            $apiUrl .= '?page=' . $page . '&per_page=' . $perPage;
            $response = Http::withToken($token)->get($apiUrl);
        }
    
        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            $data = $response->json();
    
            if (is_array($data) && array_key_exists('data', $data)) {
                $products = $data['data'];
                $total = $data['total'] ?? 0;
                $currentPage = $data['current_page'] ?? 1;
                $lastPage = $data['last_page'] ?? 1;
            } else {
                $products = array_slice($data, ($page - 1) * $perPage, $perPage);
                $total = count($data);
                $currentPage = $page;
                $lastPage = ceil($total / $perPage);
            }
    
            if ($request->has('download') && $request->input('download') === 'pdf') {
                // Guardar HTML en un archivo temporal en una ubicación accesible
                $htmlContent = view('products.pdf', compact('products'))->render();
                $htmlFilePath = storage_path('temp/my_temp_file.html');
                file_put_contents($htmlFilePath, $htmlContent);
    
                // Verificar si el archivo HTML se genera correctamente
                if (!file_exists($htmlFilePath)) {
                    return redirect()->back()->with('error', 'Error al generar el archivo HTML');
                }
    
                // Definir la ruta de salida del PDF
                $pdfFilePath = storage_path('temp/Inventario.pdf');
                $command = '"' . env('WKHTMLTOPDF_PATH') . '" --lowquality "file:///' . $htmlFilePath . '" "' . $pdfFilePath . '"';
    
                // Ejecutar el comando
                exec($command, $output, $returnVar);
    
                // Verificar si el PDF se generó correctamente
                if ($returnVar === 0) {
                    return response()->download($pdfFilePath)->deleteFileAfterSend(true);
                } else {
                    return redirect()->back()->with('error', 'Error al generar el PDF');
                }
            }
    
            return view('products.index', compact('products', 'searchQuery', 'total', 'currentPage', 'lastPage'));
        }
    
        return redirect()->back()->with('error', 'Error al obtener los productos de la API');
    }
    

    public function show($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un producto específico
        $productApiUrl = $baseApiUrl . '/api/products/' . $id;

        // URL de la API para obtener todos los proyectos
        $projectsApiUrl = $baseApiUrl . '/api/getprojects';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API para obtener los datos del producto
        $productResponse = Http::withToken($token)->get($productApiUrl);

        // Realiza una solicitud HTTP GET a la API para obtener los datos de los proyectos
        $projectsResponse = Http::withToken($token)->get($projectsApiUrl);

        if ($productResponse->successful() && $projectsResponse->successful()) {
            // Decodifica la respuesta JSON del producto en un array asociativo
            $product = $productResponse->json();

            // Decodifica la respuesta JSON de los proyectos en un array asociativo
            $projects = $projectsResponse->json();

            // Muestra la vista de detalles del producto con los datos del producto y los proyectos
            return view('products.show', compact('product', 'projects'));
        }

        // En caso de error en alguna solicitud, redirige a una vista de error o de no encontrado
        return abort(404, 'Product or projects data not found.');
    }

    public function storeEntrance(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'project_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'responsible' => 'required|string|max:100',
            'quantity' => 'required|integer',
            'description' => 'nullable|string|max:100',
            'folio' => 'nullable|string|max:100',    
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu segunda API para almacenar datos
        $apiUrl = $baseApiUrl . '/api/entrances';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a tu segunda API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);
        // dd($validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una vista con un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Entrada creada exitosamente.');
        }

        // Manejar errores de la API
        $error = $response->json('error', 'Ocurrió un error desconocido.');

        return redirect()->back()->withErrors(['quantity' => $error])->withInput();
    }

    public function storeOutputs(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'project_id' => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'responsible' => 'required|string|max:100',
            'quantity' => 'required|integer',
            'description' => 'nullable|string|max:100',
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu segunda API para almacenar datos
        $apiUrl = $baseApiUrl . '/api/outputs';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP POST a tu segunda API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una vista con un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Salida creada exitosamente.');
        }

        // Manejar errores de la API
        $error = $response->json('error', 'Ocurrió un error desconocido.');

        return redirect()->back()->withErrors(['quantity' => $error])->withInput();
    }

    public function storeLoans(Request $request) {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'project_id' => 'nullable|integer',
            'responsible' => 'required|string|max:100',
            'quantity' => 'required|integer',
            'observations' => 'nullable|string|max:255',
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu segunda API para almacenar datos
        $apiUrl = $baseApiUrl . '/api/loans';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // dd($validatedData);


        // Realizar una solicitud HTTP POST a tu segunda API con los datos validados del formulario
        $response = Http::withToken($token)->post($apiUrl, $validatedData);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una vista con un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Préstamo creado exitosamente.');
        }

        // Manejar errores de la API
        $error = $response->json('error', 'Ocurrió un error desconocido.');

        return redirect()->back()->withErrors(['quantity' => $error])->withInput();
    }

    public function loansGet($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');
    
        // URL de la API para obtener un producto específico
        $productApiUrl = $baseApiUrl . '/api/products/' . $id;
    
        // URL de la API para obtener todos los proyectos
        $projectsApiUrl = $baseApiUrl . '/api/getprojects';
    
        // Obtener el token de la sesión
        $token = $request->session()->get('token');
    
        // Realiza una solicitud HTTP GET a la API para obtener los datos del producto
        $productResponse = Http::withToken($token)->get($productApiUrl);
    
        // Realiza una solicitud HTTP GET a la API para obtener los datos de los proyectos
        $projectsResponse = Http::withToken($token)->get($projectsApiUrl);
    
        if ($productResponse->successful() && $projectsResponse->successful()) {
            // Decodifica la respuesta JSON del producto en un array asociativo
            $product = $productResponse->json();
    
            // Decodifica la respuesta JSON de los proyectos en un array asociativo
            $projects = $projectsResponse->json();
    
            // Muestra la vista de detalles del producto con los datos del producto y los proyectos
            return view('products.loans', compact('product', 'projects'));
        }
    
        // En caso de error en alguna solicitud, redirige a una vista de error o de no encontrado
        return abort(404, 'Product or projects data not found.');
    }
    
    public function outPutGet($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener un producto específico
        $productApiUrl = $baseApiUrl . '/api/products/' . $id;

        // URL de la API para obtener todos los proyectos
        $projectsApiUrl = $baseApiUrl . '/api/getprojects';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realiza una solicitud HTTP GET a la API para obtener los datos del producto
        $productResponse = Http::withToken($token)->get($productApiUrl);

        // Realiza una solicitud HTTP GET a la API para obtener los datos de los proyectos
        $projectsResponse = Http::withToken($token)->get($projectsApiUrl);

        if ($productResponse->successful() && $projectsResponse->successful()) {
            // Decodifica la respuesta JSON del producto en un array asociativo
            $product = $productResponse->json();

            // Decodifica la respuesta JSON de los proyectos en un array asociativo
            $projects = $projectsResponse->json();

            // Muestra la vista de detalles del producto con los datos del producto y los proyectos
            return view('products.output', compact('product', 'projects'));
        }

        // En caso de error en alguna solicitud, redirige a una vista de error o de no encontrado
        return abort(404, 'Product or projects data not found.');
    }



    public function create(Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener categorías y proveedores
        $apiUrl = $baseApiUrl . '/api/getCategoryProducts';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP GET a la API
        $response = Http::withToken($token)->get($apiUrl)->json();

        $suppliers = $response['suppliers'];
        $categories = $response['categories'];

        // Verificar el rol del usuario desde la sesión
        $userRole = $request->session()->get('role');
        if ($userRole == 2) {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

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
            'supplier_id' => 'nullable|integer',
        ]);

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de tu API para almacenar productos
        $apiUrl = $baseApiUrl . '/api/products';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Verificar si la solicitud contiene una imagen
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageContents = file_get_contents($file->getPathname());
            $imageName = $file->getClientOriginalName();

            // Realizar una solicitud HTTP POST a tu API con los datos validados del formulario
            $response = Http::withToken($token)->attach(
                'profile_image',
                $imageContents,
                $imageName
            )->post($apiUrl, $validatedData);
        } else {
            // Si no hay imagen adjunta, elimina el campo de imagen de los datos validados
            unset($validatedData['profile_image']);

            // Realizar una solicitud HTTP POST a tu API sin el campo de imagen
            $response = Http::withToken($token)->post($apiUrl, $validatedData);
        }

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return back()->withInput()->withErrors('Error al crear el producto. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id, Request $request) {
        // Verificar el rol del usuario desde la sesión
        $userRole = $request->session()->get('role');

        if ($userRole == 2) {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para obtener categorías y proveedores
        $apiUrl = $baseApiUrl . '/api/getCategoryProducts';

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP GET a la API
        $response = Http::withToken($token)->get($apiUrl)->json();

        $suppliers = $response['suppliers'];
        $categories = $response['categories'];

        // URL de la API para obtener un producto específico
        $productApiUrl = $baseApiUrl . '/api/products/' . $id;

        // Realiza una solicitud HTTP GET a la API para obtener los datos del producto
        $productResponse = Http::withToken($token)->get($productApiUrl);

        // Verifica si la solicitud fue exitosa
        if ($productResponse->successful()) {
            // Decodifica la respuesta JSON en un array asociativo
            $product = $productResponse->json();

            // Muestra el formulario de edición con los datos del producto
            return view('products.edit', compact('product', 'suppliers', 'categories'));
        }

        // Manejar errores si la solicitud no fue exitosa
        return back()->withErrors('Error al obtener los datos del producto. Por favor, inténtalo de nuevo más tarde.');
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
            'supplier_id' => 'nullable|integer',
        ]);

        // Configurar los datos del formulario
        $formParams = [
            [
                'name' => '_method',
                'contents' => 'PUT',
            ],
            [
                'name' => 'name',
                'contents' => $validatedData['name'],
            ],
            [
                'name' => 'model',
                'contents' => $validatedData['model'] ?? null,
            ],
            [
                'name' => 'measurement_unit',
                'contents' => $validatedData['measurement_unit'] ?? null,
            ],
            [
                'name' => 'brand',
                'contents' => $validatedData['brand'] ?? null,
            ],
            [
                'name' => 'quantity',
                'contents' => $validatedData['quantity'],
            ],
            [
                'name' => 'description',
                'contents' => $validatedData['description'] ?? null,
            ],
            [
                'name' => 'price',
                'contents' => $validatedData['price'],
            ],
            [
                'name' => 'serie',
                'contents' => $validatedData['serie'] ?? null,
            ],
            [
                'name' => 'observations',
                'contents' => $validatedData['observations'] ?? null,
            ],
            [
                'name' => 'location',
                'contents' => $validatedData['location'] ?? null,
            ],
            [
                'name' => 'category_id',
                'contents' => $validatedData['category_id'],
            ],
            [
                'name' => 'supplier_id',
                'contents' => $validatedData['supplier_id'] ?? null,
            ],
        ];


        // Comprobar si la solicitud contiene una imagen
        if ($request->hasFile('profile_image')) {
            // Procesar la nueva imagen
            $file = $request->file('profile_image');
            $filePath = $file->getPathname();
            $fileName = $file->getClientOriginalName();

            $formParams[] = [
                'name' => 'profile_image',
                'contents' => fopen($filePath, 'r'),
                'filename' => $fileName,
            ];
        }

        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para actualizar un producto específico
        $apiUrl = $baseApiUrl . '/api/products/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Crear un cliente Guzzle
        $client = new Client();

        // Realizar una solicitud HTTP POST con _method=PUT para actualizar el producto
        try {
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'multipart' => $formParams,
            ]);

            // Verificar si la solicitud fue exitosa
            if ($response->getStatusCode() == 200) {
                // Redirigir a una página de éxito o mostrar un mensaje de éxito
                return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
            } else {
                // Manejar errores si la solicitud no fue exitosa
                return back()->withInput()->withErrors('Error al actualizar el producto. Por favor, inténtalo de nuevo más tarde.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el producto: ' . $e->getMessage());
        }
    }



    public function destroy($id, Request $request) {
        // URL base de la API
        $baseApiUrl = config('app.backend_api');

        // URL de la API para eliminar un producto específico
        $apiUrl = $baseApiUrl . '/api/products/' . $id;

        // Obtener el token de la sesión
        $token = $request->session()->get('token');

        // Realizar una solicitud HTTP DELETE a la API
        $response = Http::withToken($token)->delete($apiUrl);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
        } else {
            // Si la solicitud no fue exitosa, redirigir de vuelta con un mensaje de error
            return redirect()->back()->with('error', 'No se pudo eliminar el producto. Porque tiene entradas, salidas o préstamos.');
        }
    }
}
