<?php
require_once 'includes/database.php';

try {
    $db = Database::getInstance();
    $db->crearTablas();
    echo "Base de datos inicializada correctamente.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 