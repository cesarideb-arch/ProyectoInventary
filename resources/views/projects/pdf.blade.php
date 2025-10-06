<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proyectos - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .page-break {
            page-break-before: always;
        }
        table {
            font-size: 12px;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center">Lista de Proyectos</h1>
        @php
            $chunks = array_chunk($projects, 16);
        @endphp
        @foreach($chunks as $index => $chunk)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Nombre de la Empresa</th>
                            <th class="text-center">RFC</th>
                            <th class="text-center">Dirección</th>
                            <th class="text-center">Ubicación</th>
                            <th class="text-center">Teléfono</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Nombre del Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $project)
                            <tr>
                                <td class="text-center">{{ $project['name'] }}</td>
                                <td class="text-center">{{ $project['description'] ?? 'N/A' }}</td>
                                <td class="text-center">{{ $project['company_name'] }}</td>
                                <td class="text-center">{{ $project['rfc'] ?? 'N/A' }}</td>
                                <td class="text-center">{{ $project['address'] }}</td>
                                <td class="text-center">{{ $project['ubicacion'] ?? 'N/A' }}</td>
                                <td class="text-center">{{ $project['phone_number'] }}</td>
                                <td class="text-center">{{ $project['email'] }}</td>
                                <td class="text-center">{{ $project['client_name'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>