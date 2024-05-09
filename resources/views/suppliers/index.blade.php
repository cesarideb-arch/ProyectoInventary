@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos para la ventana emergente */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            text-align: center;
        }

        .modal-content i {
            color: red;
            font-size: 24px;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-buttons button.cancel {
            background-color: #ccc;
        }

        .modal-buttons button.confirm {
            background-color: #ff3b30;
            color: white;
        }

        .btn-group-horizontal {
            display: flex;
            align-items: center;
        }

        .btn-group-horizontal .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Lista de Proveedores</h1>
        <div class="mb-3">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
        </div>
        <div class="table-responsive">
            @if(isset($suppliers) && count($suppliers) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Precio</th>
                    <th>Empresa</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th style="text-align: center;" colspan="2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                    <td>{{ $supplier['article'] }}</td>
                    <td>${{ $supplier['price'] }}</td>
                    <td>{{ $supplier['company'] }}</td>
                    <td>{{ $supplier['phone'] }}</td>
                    <td>{{ $supplier['email'] }}</td>
                    <td>{{ $supplier['address'] }}</td>
                    <td>
                        <div class="btn-group btn-group-horizontal text-center" role="group">
                        <form action="{{ route('suppliers.edit', $supplier['id']) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-custom-size">Editar</button>
                        </form>
                        <form action="{{ route('suppliers.destroy', $supplier['id']) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-custom-size"><i class="fas fa-trash"></i></button>
                        </form>
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <p>No se encontraron proveedores.</p>
            @endif
        </div>
    </div>

    <!-- JavaScript para la ventana emergente de confirmación de eliminación -->
    <script>
    const deleteForms = document.querySelectorAll('.delete-form'); // Selecciona todos los formularios de eliminar

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la acción predeterminada del formulario
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar este proveedor?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar el formulario
                    form.submit();
                }
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
