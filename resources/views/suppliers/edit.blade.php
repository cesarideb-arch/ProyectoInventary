<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .is-invalid .form-control {
            border-color: #dc3545;
        }
        .is-valid .form-control {
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Editar Proveedor</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('suppliers.update', $supplier['id']) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="article">Artículo:</label>
                <input type="text" id="article" name="article" value="{{ $supplier['article'] }}" required class="form-control @error('article') is-invalid @enderror">
                @error('article')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el artículo del proveedor.</div>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" value="{{ $supplier['price'] }}" step="0.01" required class="form-control @error('price') is-invalid @enderror">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el precio del artículo.</div>
            </div>

            <div class="form-group">
                <label for="company">Empresa:</label>
                <input type="text" id="company" name="company" value="{{ $supplier['company'] }}" required class="form-control @error('company') is-invalid @enderror">
                @error('company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el nombre de la empresa.</div>
            </div>

            <div class="form-group">
                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="phone" value="{{ $supplier['phone'] }}" required class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el número de teléfono.</div>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ $supplier['email'] }}" required class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el email del proveedor.</div>
            </div>

            <div class="form-group">
                <label for="address">Dirección:</label>
                <input type="text" id="address" name="address" value="{{ $supplier['address'] }}" required class="form-control @error('address') is-invalid @enderror">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la dirección del proveedor.</div>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var requiredInputs = ['article', 'price', 'company', 'phone', 'email', 'address'];

            for (var i = 0; i < requiredInputs.length; i++) {
                var input = document.getElementById(requiredInputs[i]);
                if (input.value === '') {
                    input.classList.add('is-invalid');
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            }

            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function () {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    this.nextElementSibling.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
