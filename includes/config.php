<?php
// Configuración de la API Gemini
define('GEMINI_API_KEY', 'AIzaSyDtSmBUq-yvjNbNqA2Rf9T5VASzNUbVSrc');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'danielb');
define('DB_PASS', '159753456');
define('DB_NAME', 'traductor_lsc');

// Configuración de la aplicación
define('MAX_VIDEO_DURATION', 30); // duración máxima en segundos
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB máximo 