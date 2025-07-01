<?php
// Test simple de la API Gemini
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';

echo "<h1>Test de API Gemini</h1>";

// Test simple con solo texto
$apiUrl = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;
$requestData = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => 'Di "Hola, la API funciona correctamente" en español.'
                ]
            ]
        ]
    ]
];

echo "<h2>1. Enviando request de prueba...</h2>";
echo "<pre>URL: " . $apiUrl . "</pre>";

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

echo "<h2>2. Respuesta:</h2>";
echo "<p>HTTP Code: $httpCode</p>";

if ($curlError) {
    echo "<p style='color:red'>Error CURL: $curlError</p>";
}

if ($httpCode === 200) {
    echo "<p style='color:green'>✅ Conexión exitosa</p>";
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        echo "<p><strong>Respuesta de Gemini:</strong> " . htmlspecialchars($result['candidates'][0]['content']['parts'][0]['text']) . "</p>";
    } else {
        echo "<p style='color:orange'>Respuesta inesperada:</p>";
        echo "<pre>" . htmlspecialchars(print_r($result, true)) . "</pre>";
    }
} else {
    echo "<p style='color:red'>❌ Error en la respuesta</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

echo "<hr>";
echo "<h2>3. Información adicional:</h2>";
echo "<p>Modelo usado: gemini-1.5-flash</p>";
echo "<p>API Key: " . (strlen(GEMINI_API_KEY) > 0 ? "Configurada (" . strlen(GEMINI_API_KEY) . " caracteres)" : "NO configurada") . "</p>";
?> 