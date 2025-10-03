<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>I-DEB ERP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* === HEADER === */
    .header {
      background-color: #000;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      height: 60px;
    }

    .header-logo {
      display: flex;
      align-items: center;
    }

    .header-logo img {
      max-height: 40px;
      width: auto;
    }

    .header-logo p {
      margin: 0 0 0 10px;
      font-size: 18px;
      font-weight: bold;
    }

    .user-info {
      display: flex;
      align-items: center;
    }

    .user-info span {
      margin-right: 15px;
    }

    /* === SIDEBAR === */
    .sidebar {
      position: fixed;
      top: 60px;
      left: 0;
      height: calc(100vh - 60px);
      width: 250px;
      background-color: #000;
      color: #fff;
      transition: width 0.3s ease, transform 0.3s ease;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      z-index: 999;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar-logo {
      text-align: center;
      padding: 20px 10px;
      border-bottom: 1px solid #444;
      display: none; /* Ocultamos el logo en sidebar ya que está en header */
    }

    .toggle-btn {
      background-color: transparent;
      color: #fff;
      border: none;
      font-size: 20px;
      cursor: pointer;
      padding: 10px 20px;
      text-align: left;
      outline: none;
      display: flex;
      align-items: center;
    }

    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
      flex-grow: 1;
    }

    .sidebar-menu li {
      width: 100%;
    }

    .sidebar-menu li a,
    .sidebar-menu li form button {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: #e1e1e1;
      text-decoration: none;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      transition: background-color 0.2s ease, color 0.2s ease;
    }

    .sidebar-menu li a:hover,
    .sidebar-menu li form button:hover {
      background-color: #333;
    }

    .sidebar-menu i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar.collapsed .sidebar-menu span {
      display: none;
    }

    .sidebar.collapsed .toggle-btn {
      text-align: center;
      justify-content: center;
    }

    .logout {
      border-top: 1px solid #444;
    }

    /* === MAIN CONTENT === */
    .main-container {
      margin-left: 250px;
      padding: 20px;
      width: calc(100% - 250px);
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin-top: 60px;
      flex: 1;
      min-height: calc(100vh - 60px - 80px); /* Ajuste para el footer */
    }

    .main-container.collapsed {
      margin-left: 70px;
      width: calc(100% - 70px);
    }

    /* === FOOTER CORREGIDO === */
    .footer {
      background-color: #000;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-wrap: wrap;
      text-align: center;
      width: 100%;
      box-sizing: border-box;
      margin-top: auto;
      z-index: 1000;
      margin-left: 250px;
      transition: margin-left 0.3s ease, width 0.3s ease;
      position: relative;
    }

    .footer.collapsed {
      margin-left: 70px;
      width: calc(100% - 70px);
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      width: 100%;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .footer-section {
      flex: 1;
      min-width: 200px;
      margin: 10px;
      text-align: left;
    }

    .footer-section h4 {
      margin-top: 0;
      margin-bottom: 10px;
      font-size: 1.2rem;
      color: #007bff;
      border-bottom: none;
      padding-bottom: 0;
    }

    .footer-section.footer-social {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }

    .footer-section.footer-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .footer-logo img {
      max-width: 120px;
      height: auto;
      margin-bottom: 10px;
    }

    .social-icons-container {
      display: flex;
      flex-direction: row;
      justify-content: flex-end;
      align-items: center;
      gap: 15px;
      margin-top: 10px;
    }

    .footer-contact p {
      margin: 5px 0;
      font-size: 0.9rem;
      color: white;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-contact p i {
      color: white;
    }

    .footer-social a {
      display: inline-block;
      color: white;
      text-decoration: none;
      font-size: 1.5rem;
      margin: 0;
    }

    .footer-social a:hover {
      color: #ccc;
    }

    .footer-version {
      color: #ccc;
      font-size: 0.8rem;
      border-top: 1px solid #444;
      padding-top: 5px;
      text-align: right;
      width: 100%;
      margin-top: 10px;
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
      cursor: pointer;
      background-color: #000;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 10px;
      font-size: 20px;
    }

    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        width: 250px;
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-container {
        margin-left: 0 !important;
        width: 100% !important;
      }
      
      .footer {
        margin-left: 0 !important;
        width: 100% !important;
      }
      
      .menu-toggle {
        display: block;
      }
      
      .header-logo {
        margin-left: 40px;
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
      }
      
      .footer-section {
        text-align: center;
      }
      
      .footer-section.footer-social {
        align-items: center;
      }
      
      .footer-section.footer-logo {
        order: -1; /* Mover el logo al inicio en móviles */
      }
      
      .social-icons-container {
        justify-content: center;
      }
      
      .footer-version {
        text-align: center;
      }
    }

    @media (max-width: 576px) {
      .user-info span {
        display: none;
      }
      
      .footer-section {
        min-width: 100%;
      }
      
      .header-logo p {
        display: none; /* Ocultar el texto del logo en móviles muy pequeños */
      }
    }
  </style>
</head>
<body>
  <!-- Botón para móviles -->
  <button class="menu-toggle" id="mobile-toggle">
    <i class="fas fa-bars"></i>
  </button>

  <!-- HEADER -->
  <div class="header">
    <div class="header-logo">
      <img src="{{ asset('images/logo.jpeg') }}" alt="Logo I-DEB México">
      <p>I-DEB México</p>
    </div>
    
    <div class="user-info">
      @if(session()->has('name'))
        <span>Bienvenido, <strong>{{ session('name') }}</strong></span>
      @endif
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-light">Salir</button>
      </form>
    </div>
  </div>

  <!-- SIDEBAR -->
  <nav class="sidebar" id="sidebar">
    <!-- Botón retraíble -->
    <button class="toggle-btn" id="toggleSidebar">
      <i class="fas fa-angle-double-left"></i><span></span>
    </button>

    <ul class="sidebar-menu">
      @if (session('role') !== '1' && session('role') !== '2')
        <li><a href="{{ route('start.index') }}"><i class="fas fa-home"></i><span> Inicio</span></a></li>
        <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i><span> Usuarios</span></a></li>
      @endif
      <li><a href="{{ route('products.index') }}"><i class="fas fa-boxes"></i><span> Inventario</span></a></li>
      @if (session('role') === '0' || session('role') === '1')
        <li><a href="{{ route('categories.index') }}"><i class="fas fa-tags"></i><span> Categorías</span></a></li>
      @endif
      <li><a href="{{ route('suppliers.index') }}"><i class="fas fa-truck"></i><span> Proveedores</span></a></li>
      @if (session('role') === '0' || session('role') === '1')
        <li><a href="{{ route('projects.index') }}"><i class="fas fa-project-diagram"></i><span> Proyectos</span></a></li>
      @endif
      <li><a href="{{ route('entrances.index') }}"><i class="fas fa-sign-in-alt"></i><span> Entradas</span></a></li>
      <li><a href="{{ route('outputs.index') }}"><i class="fas fa-sign-out-alt"></i><span> Salidas</span></a></li>
      @if (session('role') === '0' || session('role') === '1')
        <li><a href="{{ route('loans.index') }}"><i class="fas fa-hand-holding"></i><span> Préstamos</span></a></li>
      @endif
      @if (session('role') !== '1' && session('role') !== '2')
        <li><a href="{{ route('databases.index') }}"><i class="fas fa-database"></i><span> Bases de datos</span></a></li>
      @endif
      <li class="logout">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"><i class="fas fa-sign-out-alt"></i><span> Salir</span></button>
        </form>
      </li>
    </ul>
  </nav>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="main-container" id="mainContainer">
    @yield('content')
  </div>

  <!-- FOOTER CORREGIDO (solo uno) -->
  <footer class="footer" id="footer">
    <div class="footer-content">
      <div class="footer-section footer-contact">
        <h4>Contacto</h4>
        <p><i class="fas fa-map-marker-alt"></i> Fuente Alpaca #271, Villa Fontana, San Pedro Tlaquepaque, Jal.</p>
        <p><i class="fas fa-envelope"></i> contacto@idebmexico.com</p>
        <p><i class="fas fa-phone"></i> 33 1592 2676</p>
      </div>
      <div class="footer-section footer-logo">
        <img src="{{ asset('images/mexico.jpeg') }}" alt="Logo I-DEB México">
        <p>I-DEB México</p>
      </div>
      <div class="footer-section footer-social">
        <h4>Redes</h4>
        <div class="social-icons-container">
          <a href="https://www.facebook.com/IvDEB/?locale=es_LA" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://mx.linkedin.com/company/i-deb" target="_blank" aria-label="Linkedin"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-version">
      Versión 3.0
    </div>
  </footer>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- JavaScript para toggle -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const container = document.getElementById('mainContainer');
    const footer = document.getElementById('footer');
    const mobileToggle = document.getElementById('mobile-toggle');

    // Función para toggle del sidebar
    function toggleSidebar() {
      sidebar.classList.toggle('collapsed');
      container.classList.toggle('collapsed');
      footer.classList.toggle('collapsed');

      const icon = toggleBtn.querySelector('i');
      const text = toggleBtn.querySelector('span');

      if (sidebar.classList.contains('collapsed')) {
        icon.classList.replace('fa-angle-double-left', 'fa-angle-double-right');
        text.textContent = '';
      } else {
        icon.classList.replace('fa-angle-double-right', 'fa-angle-double-left');
        text.textContent = '';
      }
      
      // Guardar estado en localStorage
      localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    // Cargar estado del sidebar
    document.addEventListener('DOMContentLoaded', function() {
      const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (isCollapsed) {
        sidebar.classList.add('collapsed');
        container.classList.add('collapsed');
        footer.classList.add('collapsed');
        const icon = toggleBtn.querySelector('i');
        const text = toggleBtn.querySelector('span');
        icon.classList.replace('fa-angle-double-left', 'fa-angle-double-right');
        text.textContent = '';
      }
    });

    toggleBtn.addEventListener('click', toggleSidebar);

    // Mostrar/ocultar el menú en pantallas pequeñas
    mobileToggle.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });

    // Cerrar menú al hacer clic fuera en móviles
    document.addEventListener('click', function(event) {
      if (window.innerWidth <= 992 && 
          !sidebar.contains(event.target) && 
          event.target !== mobileToggle &&
          !mobileToggle.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });

    // Logout con confirmación
    const logoutButtons = document.querySelectorAll('form[action="{{ route('logout') }}"] button');
    logoutButtons.forEach(button => {
      button.addEventListener('click', function (event) {
        event.preventDefault();
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
            this.closest("form").submit();
          }
        }); 
      });
    });
  </script>
</body>
</html>