<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        
        p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .status {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        
        .status.success {
            border-left-color: #28a745;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Sistema de Inventario</h1>
        <p>Bienvenido al sistema de gestión de inventario. Tu aplicación está funcionando correctamente en el entorno de producción.</p>
        
        <div class="status success">
            ✅ Sistema operativo - Frontend cargado exitosamente
        </div>
        
        <a href="/login" class="btn">Iniciar Sesión</a>
        
        <div style="margin-top: 20px; font-size: 0.9em; color: #888;">
            <p>Si el botón no funciona, <a href="/login" style="color: #667eea;">haz clic aquí</a></p>
        </div>
    </div>

    <script>
        // Verificar que JavaScript está funcionando
        console.log('Sistema de Inventario - Frontend cargado correctamente');
        
        // Agregar animación simple al botón
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.btn');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    console.log('Redirigiendo al login...');
                    // La redirección se hace por el href, esto es solo backup
                });
            }
        });
    </script>
</body>
</html>