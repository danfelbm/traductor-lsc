<?php
// Iniciar output buffering para capturar cualquier warning
ob_start();

// Configurar logging de errores - silencioso en producción
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Crear archivo de log si no existe
$logFile = __DIR__ . '/error.log';
ini_set('error_log', $logFile);

// Función para logging personalizado
function logError($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    error_log($logMessage, 3, $logFile);
}

// Suprimir avisos de configuración en este contexto
define('SUPPRESS_CONFIG_WARNING', true);

require_once 'includes/config.php';
require_once 'includes/database.php';

// Limpiar cualquier output que haya ocurrido hasta ahora
ob_clean();

header('Content-Type: application/json');

try {
    logError("Iniciando procesamiento de video");
    
    // Verificar que las credenciales estén configuradas
    if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === 'TU_API_KEY_DE_GEMINI_AQUI') {
        throw new Exception('La API Key de Gemini no está configurada. Por favor, crea el archivo includes/config.local.php con tus credenciales.');
    }
    
    // Verificar si se recibió un archivo de video
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se recibió ningún archivo de video o hubo un error en la carga.');
    }

    // Verificar el tamaño del archivo
    if ($_FILES['video']['size'] > MAX_FILE_SIZE) {
        throw new Exception('El archivo excede el tamaño máximo permitido.');
    }

    // Crear directorio para videos si no existe
    $uploadDir = 'uploads/videos/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generar nombre único para el archivo
    $fileName = uniqid() . '_' . basename($_FILES['video']['name']);
    $filePath = $uploadDir . $fileName;

    // Mover el archivo subido
    if (!move_uploaded_file($_FILES['video']['tmp_name'], $filePath)) {
        throw new Exception('Error al guardar el archivo de video.');
    }

    logError("Video guardado en: $filePath");

    // Convertir video a base64
    $videoData = base64_encode(file_get_contents($filePath));
    logError("Video convertido a base64, tamaño: " . strlen($videoData));

    // Preparar la solicitud a la API Gemini
    $apiUrl = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;
    
    // Prompt más específico para evitar respuestas de detección de objetos
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
            'temperature' => 0.3,      // Más bajo para respuestas más consistentes
            'topK' => 40,
            'topP' => 0.95,
            // 'maxOutputTokens' => 512,  // Comentado para no limitar la respuesta
            // 'candidateCount' => 1      // Comentado - valor por defecto
        ]
    ];

    logError("Request preparado, enviando a Gemini API");

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

    logError("Respuesta HTTP Code: $httpCode");
    
    if ($curlError) {
        logError("Error CURL: $curlError");
        throw new Exception('Error en la conexión: ' . $curlError);
    }

    if ($httpCode !== 200) {
        logError("Error API Response: $response");
        throw new Exception('Error en la respuesta de la API Gemini: ' . $response);
    }

    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        logError("Error decodificando JSON: " . json_last_error_msg());
        throw new Exception('Error al decodificar la respuesta JSON.');
    }
    
    // Manejo más robusto de la respuesta
    $traduccion = null;
    
    // Verificar estructura estándar
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $traduccion = trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    // Verificar si es respuesta con role pero sin parts (MAX_TOKENS)
    else if (isset($result['candidates'][0]['finishReason']) && 
             $result['candidates'][0]['finishReason'] === 'MAX_TOKENS') {
        logError("Respuesta cortada por MAX_TOKENS");
        logError("Respuesta completa: " . print_r($result, true));
        throw new Exception('La respuesta fue cortada. Por favor, graba un video más corto.');
    }
    else {
        logError("Estructura de respuesta inesperada: " . print_r($result, true));
        throw new Exception('Formato de respuesta inesperado de la API.');
    }
    
    // Limpiar la traducción de posibles formatos JSON
    if (strpos($traduccion, '```json') !== false || 
        strpos($traduccion, '[{') !== false ||
        strpos($traduccion, '"box_2d"') !== false) {
        logError("Respuesta contiene formato JSON no deseado: $traduccion");
        
        // Intentar extraer solo el texto relevante
        if (preg_match('/"label":\s*"([^"]+)"/', $traduccion, $matches)) {
            $traduccion = $matches[1];
        } else {
            throw new Exception('El modelo devolvió un formato de detección de objetos en lugar de traducción. Por favor, intenta de nuevo.');
        }
    }
    
    logError("Traducción obtenida: $traduccion");

    // Guardar en la base de datos
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("INSERT INTO traducciones (video_path, texto_traducido) VALUES (?, ?)");
    $stmt->execute([$filePath, $traduccion]);
    
    logError("Traducción guardada en base de datos");

    // Limpiar cualquier output previo antes de enviar JSON
    ob_end_clean();
    
    // Devolver respuesta exitosa
    echo json_encode([
        'success' => true,
        'traduccion' => $traduccion
    ]);

} catch (Exception $e) {
    // Log del error
    logError("ERROR: " . $e->getMessage());
    logError("Stack trace: " . $e->getTraceAsString());
    
    // Limpiar cualquier output previo antes de enviar JSON
    ob_end_clean();
    
    // Devolver error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 