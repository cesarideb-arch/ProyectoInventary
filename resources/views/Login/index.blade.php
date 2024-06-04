<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
    
        .container {
            max-width: 450px;
            margin: auto;
            padding-top: 200px;
            text-align: center;
        }
    
        .login-form {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    
        .login-heading {
            margin-bottom: 20px;
        }
    
        .login-heading img {
            max-width: 100%;
            height: auto;
        }
    
        .form-control {
            border: 1px solid #ced4da !important; /* Importante para sobrescribir los estilos de Bootstrap */
            border-radius: 4px !important; /* Importante para sobrescribir los estilos de Bootstrap */
        }
    
        .form-control:focus {
            border-color: #ced4da !important; /* Cambia el color del borde al enfocar a gris */
            box-shadow: none !important; /* Elimina la sombra al enfocar */
        }
    
        .login-button {
            margin-top: 20px;
            border-radius: 4px;
            background-color: #333 !important; /* Importante para sobrescribir los estilos de Bootstrap */
            border: 1px solid #808080 !important; /* Importante para sobrescribir los estilos de Bootstrap */
            color: #fff !important; /* Importante para sobrescribir los estilos de Bootstrap */
        }
    
        .login-button:focus {
            outline: none !important; /* Importante para sobrescribir los estilos de Bootstrap */
            box-shadow: none !important; /* Importante para sobrescribir los estilos de Bootstrap */
        }
    
        .login-button:hover {
            background-color: #666666 !important; /* Importante para sobrescribir los estilos de Bootstrap */
            border-color: #666666 !important; /* Importante para sobrescribir los estilos de Bootstrap */
        }
    
        .login-button:active {
            background-color: #333 !important; /* Importante para sobrescribir los estilos de Bootstrap */
            border-color: #333 !important; /* Importante para sobrescribir los estilos de Bootstrap */
            outline: none !important; /* Importante para sobrescribir los estilos de Bootstrap */
        }
    
        .error-message {
            display: block;
            margin-top: 10px; /* Mueve el mensaje de error un poco más abajo */
            color: #dc3545; /* Color rojo */
        }

        /* Cambia el color de selección del texto */
        ::selection {
            background: #333 !important; /* Cambia este color al que prefieras */
            color: #fff !important; /* Cambia este color al que prefieras */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <div class="login-heading">
                <div class="login-heading text-center">
                    <img src="{{ asset('fav.png') }}" alt="Logo" style="margin-left: -10px;">
                    <link rel="icon" href="/favicon.ico" type="image/x-icon">
                </div>
                <link rel="icon" href="/favicon.ico" type="image/x-icon">
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    @if (session('error'))
                        <span class="error-message font-weight-bold text-danger">{{ session('error') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-block login-button">Iniciar sesión</button>
            </form>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            var passwordFieldType = passwordField.getAttribute('type');
            var toggleIcon = this.querySelector('i');
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.setAttribute('type', 'password');
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>

