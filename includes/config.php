<?php
// Configuración principal del proyecto
// Las credenciales sensibles están en config.local.php (no incluido en Git)

// Cargar configuración local si existe
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
} else {
    // Configuración de desarrollo/ejemplo - CAMBIAR EN PRODUCCIÓN
    define('GEMINI_API_KEY', 'TU_API_KEY_DE_GEMINI_AQUI');
    define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'tu_usuario_db');
    define('DB_PASS', 'tu_contraseña_db');
    define('DB_NAME', 'traductor_lsc');
    
    // Mostrar aviso de configuración
    if (!defined('SUPPRESS_CONFIG_WARNING')) {
        echo '<div style="background:#ff9800;color:white;padding:10px;margin:10px;border-radius:5px;">';
        echo '<strong>⚠️ CONFIGURACIÓN:</strong> Copia includes/config.example.php como includes/config.local.php y configura tus credenciales.';
        echo '</div>';
    }
}

// Configuración de la aplicación
define('MAX_VIDEO_DURATION', 30); // duración máxima en segundos
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB máximo 