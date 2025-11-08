<?php
// TEMPORAL: Activar errores para diagnóstico

use PSpell\Config;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

// Cargar autoloader de Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}


// Iniciar buffer de salida para capturar errores
ob_start();

try {
    session_start();
} catch (Exception $e) {
    die('Error de sesión: ' . $e->getMessage());
}

include_once(__DIR__ . "/helper/ConfigFactory.php");
$configFactory = new ConfigFactory();
$router = $configFactory->get('Router');

$router->executeController(isset($_GET['controller']) ? $_GET['controller'] : null, isset($_GET['method']) ? $_GET['method'] : null);
