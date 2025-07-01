<?php
// Configurar logging de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';

// Verificar que las credenciales estén configuradas
if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === 'TU_API_KEY_DE_GEMINI_AQUI') {
    die('La API Key de Gemini no está configurada. Por favor, crea el archivo includes/config.local.php con tus credenciales.');
}

echo "<h1>Test de API Gemini</h1>";
echo "<p>Modelo: " . GEMINI_MODEL . "</p>";

// Preparar la solicitud a la API Gemini
$apiUrl = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;

// Usar un video de prueba simple
$videoTestPath = 'uploads/videos/test_video.mp4';
if (!file_exists($videoTestPath)) {
    die("No se encontró el archivo de prueba: $videoTestPath");
}

$videoData = base64_encode(file_get_contents($videoTestPath));

$requestData = [
    'systemInstruction' => [
        'parts' => [
            [
                'text' => 'Eres un intérprete profesional de Lengua de Señas Colombiana (LSC). Tu ÚNICA función es traducir las señas de LSC al español. NUNCA uses formato JSON, NUNCA describas el video, NUNCA respondas en inglés. Solo traduce las señas al español o di "No se detectaron señas de LSC en el video".'
            ]
        ]
    ],
    'contents' => [
        [
            'parts' => [
                [
                    'text' => 'Traduce las señas de este video al español:'
                ],
                [
                    'inline_data' => [
                        'mime_type' => 'video/mp4',
                        'data' => $videoData
                    ]
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.3,
        'topK' => 40,
        'topP' => 0.95,
        // 'maxOutputTokens' => 512,  // Comentado para no limitar la respuesta
        // 'candidateCount' => 1      // Comentado - valor por defecto
    ]
];

echo "<h2>Enviando solicitud a Gemini API...</h2>";

// Realizar la solicitud a la API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo "<p style='color: red;'>Error CURL: $curlError</p>";
    exit;
}

echo "<p>Código HTTP: $httpCode</p>";

if ($httpCode !== 200) {
    echo "<p style='color: red;'>Error en la respuesta:</p>";
    echo "<pre>$response</pre>";
    exit;
}

$result = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p style='color: red;'>Error al decodificar JSON: " . json_last_error_msg() . "</p>";
    exit;
}

echo "<h2>Respuesta completa:</h2>";
echo "<pre>" . print_r($result, true) . "</pre>";

// Extraer la traducción
$traduccion = null;

if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $traduccion = trim($result['candidates'][0]['content']['parts'][0]['text']);
} else if (isset($result['candidates'][0]['finishReason']) && 
           $result['candidates'][0]['finishReason'] === 'MAX_TOKENS') {
    echo "<p style='color: orange;'>La respuesta fue cortada por MAX_TOKENS</p>";
} else {
    echo "<p style='color: red;'>Formato de respuesta inesperado</p>";
}

if ($traduccion) {
    // Verificar si la respuesta contiene formato JSON no deseado
    if (strpos($traduccion, '```json') !== false || 
        strpos($traduccion, '[{') !== false ||
        strpos($traduccion, '"box_2d"') !== false) {
        echo "<p style='color: orange;'>Advertencia: La respuesta contiene formato JSON</p>";
        
        // Intentar extraer solo el texto relevante
        if (preg_match('/"label":\s*"([^"]+)"/', $traduccion, $matches)) {
            $traduccion = $matches[1];
            echo "<p>Texto extraído: $traduccion</p>";
        }
    }
    
    echo "<h2 style='color: green;'>Traducción:</h2>";
    echo "<p style='font-size: 1.2em; padding: 20px; background: #f0f0f0; border-radius: 5px;'>$traduccion</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Volver al inicio</a></p>";
?> 