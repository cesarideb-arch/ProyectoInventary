@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Crear Usuario</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="role">Rol</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">Selecciona un rol</option>
                    <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Admin Trabajador</option>
                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Trabajador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="admin_password">Contraseña de Administrador</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('admin_password', this)">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Agregar más campos si es necesario -->

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        function togglePasswordVisibility(fieldId, toggleButton) {
            var passwordField = document.getElementById(fieldId);
            var passwordFieldType = passwordField.getAttribute('type');
            var toggleIcon = toggleButton.querySelector('i');
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordField.setAttribute('type', 'password');
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>
@endsection
