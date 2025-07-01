<?php
// Archivo de ejemplo de configuración
// Copia este archivo como config.local.php y completa con las credenciales reales

// Configuración de la API Gemini
define('GEMINI_API_KEY', 'TU_API_KEY_DE_GEMINI_AQUI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent');
define('GEMINI_MODEL', 'gemini-2.5-pro');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_db');
define('DB_PASS', 'tu_contraseña_db');
define('DB_NAME', 'traductor_lsc');

// NOTA: MAX_VIDEO_DURATION y MAX_FILE_SIZE se definen en config.php
// No las definas aquí para evitar warnings de constantes duplicadas 