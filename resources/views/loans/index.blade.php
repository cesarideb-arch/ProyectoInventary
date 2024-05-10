@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
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
        <h1 class="mb-4">Listado de Préstamos</h1>
        <div class="table-responsive">
            @if(isset($loans) && count($loans) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Boton</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td>{{ $loan['product']['name'] }}</td>
                        <td>{{ $loan['responsible'] }}</td>
                        <td>{{ $loan['quantity'] }}</td>
                        <td>{{ $loan['date'] }}</td>
                        <td>
                            @if($loan['status'] == 0)
                                Producto Regresado
                            @else
                                {{ $loan['status'] }}
                            @endif
                        </td>
                        <td>
                            @if($loan['status'] == 1)
                                <div class="btn-group btn-group-horizontal" role="group">
                                    <!-- Botón de Edición -->
                                    <form action="{{ route('loans.update', $loan['id']) }}" method="POST" style="margin-right: 5px;">
                                        @csrf
                                        @method('PUT') <!-- Utilizamos el método PUT para la actualización -->
                                        <input type="hidden" name="loan_id" value="{{ $loan['id'] }}"> <!-- Campo oculto con el ID del préstamo -->
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Regresar Producto
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>No se encontraron préstamos.</p>
            @endif
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
