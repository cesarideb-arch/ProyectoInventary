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
            justify-content: space-between;
            align-items: center;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .bar {
            background-color: #fff;
            height: 3px;
            width: 25px;
            margin: 3px auto;
        }

        .nav-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
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
        justify-content: center; /* Centra el ícono del menú */
    }

    .nav-list {
        position: absolute;
        top: 60px;
        left: 0;
        right: 0;
        background-color: #333;
        display: none;
        flex-direction: column;
        align-items: center; /* Centra el contenido horizontalmente */
        width: 100%; /* Asegura que el menú use todo el ancho disponible */
        z-index: 1000; /* Mantiene el menú por encima de otros elementos */
        padding: 0; /* Elimina cualquier padding innecesario */
        margin: 0; /* Elimina cualquier margen innecesario */
    }

    .nav-list.active {
        display: flex;
    }

    .nav-list li {
        width: 100%; /* Establece el ancho de cada elemento del menú al total disponible */
        text-align: center; /* Alinea el texto al centro */
        padding: 10px 0; /* Añade padding vertical para mejorar la táctilidad, sin padding horizontal */
        border-bottom: 1px solid #ffffff; /* Añade un divisor visual entre elementos, si deseado */
    }

    .nav-list li:last-child {
        border-bottom: none; /* Elimina el borde del último elemento para evitar una línea extra al final */
    }

    .nav-list li a {
        color: #fff; /* Asegura que el texto sea blanco */
        text-decoration: none; /* Elimina el subrayado de los enlaces */
        display: block; /* Hace que el enlace ocupe todo el espacio del 'li' */
    }
}
      
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul class="nav-list">
                <li><a href="{{ route('start.index') }}">Inicio</a></li>
                <li><a href="#">Empleados</a></li>
                <li><a href="{{ route('products.index') }}">Inventario</a></li>
                <li><a href="#">Proyectos</a></li>
                <li><a href="#">Entradas</a></li>
                <li><a href="#">Salidas</a></li>
                <li class="logout-form">
                    <li class="logout-form">
                        <form action="{{route("logout")}}" method="POST">
                            @csrf
                            <button type="submit" style="background-color: red;" onclick="return confirm>Cerrar sesión</button>
                        </form>
                    </li>
                
    </nav>
    
    <div class="container">
        @yield('content')
    </div>

    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const navList = document.querySelector('.nav-list');
        
        mobileMenu.addEventListener('click', () => {
            navList.classList.toggle('active');
        });

   
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11"

    </script>

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

  
