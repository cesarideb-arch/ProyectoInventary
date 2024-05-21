<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        nav {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-right: auto;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            margin-left: 10px;
        }

        .bar {
            background-color: #fff;
            height: 3px;
            width: 25px;
            margin: 3px 0;
        }

        .nav-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .nav-list li {
            margin-right: 20px;
        }

        .nav-list li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-list li a:hover {
            color: #ffcc00;
        }

        .nav-list img {
            height: 60px;
            margin-right: 20px;
        }

        .logout-form {
            display: inline-block;
        }

        .logout-form button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .logout-form button:hover {
            background-color: #ffcc00;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
                justify-content: center;
                padding: 10px;
                align-items: center;
                margin-left: 0;
            }

            .nav-list {
                position: absolute;
                top: 140px;
                left: 0;
                right: 0;
                background-color: #333;
                display: none;
                flex-direction: column;
                align-items: flex-start; /* Alinea el menú a la izquierda */
                width: 100%;
                z-index: 1000;
                padding: 0;
                margin: 0;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .nav-list.active {
                display: flex;
            }

            .nav-list li {
                width: 100%;
                text-align: center;
                padding: 10px 0;
                border-bottom: none;
            }

            .nav-list li a {
                color: #fff;
                text-decoration: none;
                display: block;
                padding: 10px;
            }

            .nav-list img {
                height: 40px;
                margin-right: 0;
            }
        }
    </style>
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="{{ asset('favicon.ico') }}" alt="Logo" style="height: 80px;">
        </div>
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="nav-list">
            <li><a href="{{ route('start.index') }}">Inicio</a></li>
            <li><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li><a href="{{ route('products.index') }}">Inventario</a></li>
            <li><a href="{{ route('categories.index') }}">Categorías</a></li>
            <li><a href="{{ route('suppliers.index') }}">Proveedor</a></li>
            <li><a href="{{ route('projects.index') }}">Proyectos</a></li>
            <li><a href="{{ route('entrances.index') }}">Lista de Entradas</a></li>
            <li><a href="{{ route('outputs.index') }}">Lista de Salidas</a></li>
            <li><a href="{{ route('loans.index') }}">Lista de Prestamo</a></li>

            <li class="logout-form">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background-color: transparent; border: none; margin-top: 10px; outline: none;">
                        <i class="fas fa-sign-out-alt" style="color: red; font-size: 24px; border-radius: 15px; padding: 5px; background-color: rgba(255, 0, 0, 0.1);"></i>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const navList = document.querySelector('.nav-list');
        
        mobileMenu.addEventListener('click', () => {
            navList.classList.toggle('active');
        });

        const logoutButton = document.querySelector('.logout-form button'); // Selecciona el botón de cerrar sesión

        logoutButton.addEventListener('click', function(event) {
            event.preventDefault(); // Previene la acción predeterminada del formulario
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres cerrar sesión?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar el formulario
                    logoutButton.closest("form").submit();
                }
            });
        });
    </script>
</body>
</html>
