<?php
// Script de verificación de configuración
header('Content-Type: text/html; charset=UTF-8');

echo "<h1>Verificación de Configuración - Traductor LSC</h1>";

// 1. Verificar archivo config.local.php
echo "<h2>1. Archivo de Configuración Local</h2>";
$configLocal = __DIR__ . '/includes/config.local.php';
if (file_exists($configLocal)) {
    echo "✅ Archivo config.local.php existe<br>";
    
    // Cargar configuración
    define('SUPPRESS_CONFIG_WARNING', true);
    require_once 'includes/config.php';
    
    // Verificar credenciales
    echo "<h3>Credenciales:</h3>";
    
    // API Key de Gemini
    if (defined('GEMINI_API_KEY') && GEMINI_API_KEY !== 'TU_API_KEY_DE_GEMINI_AQUI') {
        echo "✅ GEMINI_API_KEY configurada (" . strlen(GEMINI_API_KEY) . " caracteres)<br>";
    } else {
        echo "❌ GEMINI_API_KEY NO configurada correctamente<br>";
    }
    
    // Credenciales de base de datos
    if (defined('DB_USER') && DB_USER !== 'tu_usuario_db') {
        echo "✅ DB_USER configurado: " . DB_USER . "<br>";
    } else {
        echo "❌ DB_USER NO configurado<br>";
    }
    
    if (defined('DB_PASS') && DB_PASS !== 'tu_contraseña_db') {
        echo "✅ DB_PASS configurado (oculto)<br>";
    } else {
        echo "❌ DB_PASS NO configurado<br>";
    }
    
    if (defined('DB_NAME')) {
        echo "✅ DB_NAME: " . DB_NAME . "<br>";
    } else {
        echo "❌ DB_NAME NO configurado<br>";
    }
    
} else {
    echo "❌ <strong>Archivo config.local.php NO existe</strong><br>";
    echo "<div style='background:#ff9800;padding:10px;margin:10px 0;border-radius:5px;'>";
    echo "<strong>Instrucciones:</strong><br>";
    echo "1. Copia el archivo <code>includes/config.example.php</code> a <code>includes/config.local.php</code><br>";
    echo "2. Edita <code>config.local.php</code> y agrega tus credenciales<br>";
    echo "3. Asegúrate de que el archivo NO se suba a Git (ya está en .gitignore)<br>";
    echo "</div>";
}

// 2. Verificar ejemplo de configuración
echo "<h2>2. Archivo de Ejemplo</h2>";
$configExample = __DIR__ . '/includes/config.example.php';
if (file_exists($configExample)) {
    echo "✅ config.example.php existe (plantilla)<br>";
} else {
    echo "❌ config.example.php NO existe<br>";
}

// 3. Test de API de Gemini (solo si está configurada)
if (defined('GEMINI_API_KEY') && GEMINI_API_KEY !== 'TU_API_KEY_DE_GEMINI_AQUI') {
    echo "<h2>3. Test de API de Gemini</h2>";
    echo "<p>Realizando prueba simple...</p>";
    
    $apiUrl = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;
    $testData = [
        'contents' => [
            [
                'parts' => [
                    ['text' => 'Responde solo: OK']
                ]
            ]
        ]
    ];
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ API de Gemini respondió correctamente (HTTP 200)<br>";
    } else {
        echo "❌ Error en API de Gemini (HTTP $httpCode)<br>";
        if ($httpCode === 400) {
            echo "Posible API Key inválida<br>";
        }
    }
}

// 4. Estructura de carpetas
echo "<h2>4. Estructura de Carpetas</h2>";
$uploadDir = __DIR__ . '/uploads/videos/';
if (is_dir($uploadDir)) {
    echo "✅ Directorio uploads/videos/ existe<br>";
    if (is_writable($uploadDir)) {
        echo "✅ Directorio es escribible<br>";
    } else {
        echo "❌ Directorio NO es escribible (verificar permisos)<br>";
    }
} else {
    echo "❌ Directorio uploads/videos/ NO existe<br>";
}

// 5. Archivo de logs
echo "<h2>5. Sistema de Logs</h2>";
$errorLog = __DIR__ . '/error.log';
if (file_exists($errorLog)) {
    echo "✅ Archivo error.log existe<br>";
    $lastLines = tail($errorLog, 5);
    if (!empty($lastLines)) {
        echo "<strong>Últimas 5 líneas del log:</strong><pre style='background:#f5f5f5;padding:10px;'>";
        echo htmlspecialchars(implode("\n", $lastLines));
        echo "</pre>";
    }
} else {
    echo "⚠️ Archivo error.log no existe aún (se creará al primer error)<br>";
}

// Función para leer últimas líneas de un archivo
function tail($filename, $lines = 10) {
    if (!file_exists($filename)) return [];
    $file = file($filename);
    return array_slice($file, -$lines);
}

echo "<hr>";
echo "<p><strong>Resumen:</strong> Revisa los puntos marcados con ❌ y corrige según las instrucciones.</p>";
?> 