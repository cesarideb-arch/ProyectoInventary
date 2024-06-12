<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proyectos - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Lista de Proyectos</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Nombre de la Empresa</th>
                        <th class="text-center">RFC</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Nombre del Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td class="text-center">{{ $project['name'] }}</td>
                        <td class="text-center">{{ $project['description'] ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project['company_name'] }}</td>
                        <td class="text-center">{{ $project['rfc'] ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project['address'] }}</td>
                        <td class="text-center">{{ $project['phone_number'] }}</td>
                        <td class="text-center">{{ $project['email'] }}</td>
                        <td class="text-center">{{ $project['client_name'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
