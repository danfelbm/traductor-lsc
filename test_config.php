<?php
// Test de configuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test de Configuración</h1>";

// Test de archivos requeridos
echo "<h2>1. Archivos requeridos:</h2>";
$files = ['includes/config.php', 'includes/database.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file NO existe<br>";
    }
}

// Test de constantes
echo "<h2>2. Constantes definidas:</h2>";
require_once 'includes/config.php';
$constants = ['GEMINI_API_KEY', 'GEMINI_API_URL', 'DB_HOST', 'DB_USER', 'DB_NAME', 'MAX_FILE_SIZE'];
foreach ($constants as $const) {
    if (defined($const)) {
        echo "✅ $const definida<br>";
    } else {
        echo "❌ $const NO definida<br>";
    }
}

// Test de directorio uploads
echo "<h2>3. Directorio uploads:</h2>";
$uploadDir = 'uploads/videos/';
if (is_dir($uploadDir)) {
    echo "✅ Directorio $uploadDir existe<br>";
    if (is_writable($uploadDir)) {
        echo "✅ Directorio $uploadDir es escribible<br>";
    } else {
        echo "❌ Directorio $uploadDir NO es escribible<br>";
    }
} else {
    echo "❌ Directorio $uploadDir NO existe<br>";
}

// Test de conexión a base de datos
echo "<h2>4. Conexión a base de datos:</h2>";
try {
    require_once 'includes/database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "✅ Conexión exitosa<br>";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

// Test de PHP Info básico
echo "<h2>5. Información PHP:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Max Upload: " . ini_get('upload_max_filesize') . "<br>";
echo "Max POST: " . ini_get('post_max_size') . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";

// Test de extensiones necesarias
echo "<h2>6. Extensiones PHP:</h2>";
$extensions = ['curl', 'json', 'pdo', 'pdo_mysql'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext cargada<br>";
    } else {
        echo "❌ $ext NO cargada<br>";
    }
}

echo "<hr><p>Test completado. Revisa el archivo error.log para más detalles.</p>";
?> 