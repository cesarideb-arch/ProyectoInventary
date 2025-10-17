<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Conexión Frontend-Backend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">🧪 Test de Conexión Frontend → Backend</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>🔍 Debug de Configuración</h5>
                            <p>Esta página te ayuda a verificar que la configuración entre frontend y backend esté correcta.</p>
                        </div>

                        <h5>📊 Configuración Detectada</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Frontend URL (APP_URL)</th>
                                <td>{{ $configInfo['frontend_url'] }}</td>
                            </tr>
                            <tr>
                                <th>API Base URL (services.api.base_url)</th>
                                <td><code>{{ $configInfo['services_api_base_url'] }}</code></td>
                            </tr>
                            <tr>
                                <th>API Base URL (env API_BASE_URL)</th>
                                <td><code>{{ $configInfo['env_api_base_url'] }}</code></td>
                            </tr>
                            <tr>
                                <th>Environment</th>
                                <td><span class="badge bg-info">{{ $configInfo['environment'] }}</span></td>
                            </tr>
                            <tr>
                                <th>Debug Mode</th>
                                <td><span class="badge {{ $configInfo['debug_mode'] ? 'bg-warning' : 'bg-secondary' }}">
                                    {{ $configInfo['debug_mode'] ? 'ACTIVADO' : 'DESACTIVADO' }}
                                </span></td>
                            </tr>
                        </table>

                        <div class="alert alert-warning">
                            <strong>⚠️ IMPORTANTE:</strong> 
                            <code>services.api.base_url</code> debe apuntar SOLO al backend: <code>http://apiinventario.idebmexico.com/api</code>
                        </div>

                        <div class="d-grid gap-2">
                            <button id="testBtn" class="btn btn-lg btn-success">
                                🚀 Probar Conexión con Backend
                            </button>
                        </div>

                        <div id="loading" class="text-center mt-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Probando conexión...</span>
                            </div>
                            <p class="mt-2">Probando conexión con el backend...</p>
                        </div>

                        <div id="results" class="mt-4" style="display: none;">
                            <h5>📋 Resultados del Test</h5>
                            <pre id="resultContent" class="p-3 rounded" style="background: #f8f9fa; border: 1px solid #dee2e6;"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testBtn').addEventListener('click', function() {
            const btn = this;
            const results = document.getElementById('results');
            const loading = document.getElementById('loading');
            const resultContent = document.getElementById('resultContent');

            // Reset y mostrar loading
            btn.disabled = true;
            btn.innerHTML = '⏳ Probando...';
            loading.style.display = 'block';
            results.style.display = 'none';

            // Hacer petición al endpoint de test
            fetch('/test-api')
                .then(response => {
                    return response.json().then(data => ({
                        status: response.status,
                        data: data
                    }));
                })
                .then(({status, data}) => {
                    // Mostrar resultados
                    resultContent.textContent = JSON.stringify(data, null, 2);
                    results.style.display = 'block';

                    // Colorear según el resultado
                    if (status === 200) {
                        resultContent.style.background = '#d1e7dd';
                        resultContent.style.borderColor = '#badbcc';
                        resultContent.style.color = '#0f5132';
                    } else {
                        resultContent.style.background = '#f8d7da';
                        resultContent.style.borderColor = '#f1aeb5';
                        resultContent.style.color = '#721c24';
                    }
                })
                .catch(error => {
                    resultContent.textContent = 'Error: ' + error.message;
                    resultContent.style.background = '#f8d7da';
                    resultContent.style.borderColor = '#f1aeb5';
                    resultContent.style.color = '#721c24';
                    results.style.display = 'block';
                })
                .finally(() => {
                    loading.style.display = 'none';
                    btn.disabled = false;
                    btn.innerHTML = '🔄 Probar Nuevamente';
                });
        });
    </script>
</body>
</html>