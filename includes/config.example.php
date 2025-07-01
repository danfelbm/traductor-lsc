<?php
// Archivo de ejemplo de configuración
// Copia este archivo como config.local.php y completa con las credenciales reales

// Configuración de la API Gemini
define('GEMINI_API_KEY', 'TU_API_KEY_DE_GEMINI_AQUI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_db');
define('DB_PASS', 'tu_contraseña_db');
define('DB_NAME', 'traductor_lsc');

// Configuración de la aplicación
define('MAX_VIDEO_DURATION', 30); // duración máxima en segundos
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB máximo 