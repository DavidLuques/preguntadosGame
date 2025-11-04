<?php
// Archivo de prueba para diagnosticar problemas
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Prueba de Diagnóstico</h1>";

echo "<h2>1. Versión de PHP:</h2>";
echo "Versión: " . phpversion() . "<br>";

echo "<h2>2. Verificación de archivos:</h2>";
$files = [
    'config/config.ini',
    'helper/MyConexion.php',
    'helper/Renderer.php',
    'controllers/LoginController.php',
    'controllers/JugadoresController.php',
    'index.php'
];

foreach ($files as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✓ $file existe<br>";
    } else {
        echo "✗ $file NO existe<br>";
    }
}

echo "<h2>3. Verificación de configuración:</h2>";
$configPath = __DIR__ . '/config/config.ini';
if (file_exists($configPath)) {
    $config = parse_ini_file($configPath);
    if ($config) {
        echo "✓ Configuración cargada correctamente<br>";
        echo "Server: " . (isset($config['server']) ? $config['server'] : 'NO DEFINIDO') . "<br>";
        echo "Username: " . (isset($config['username']) ? $config['username'] : 'NO DEFINIDO') . "<br>";
        echo "DB Name: " . (isset($config['db_name']) ? $config['db_name'] : 'NO DEFINIDO') . "<br>";
    } else {
        echo "✗ Error al parsear config.ini<br>";
    }
} else {
    echo "✗ config/config.ini NO existe<br>";
}

echo "<h2>4. Verificación de clases:</h2>";
require_once(__DIR__ . "/helper/MyConexion.php");
if (class_exists('MyConexion')) {
    echo "✓ Clase MyConexion existe<br>";
    
    // Intentar conexión si hay configuración
    if (isset($config) && $config) {
        try {
            $conexion = new MyConexion(
                $config['server'],
                $config['username'],
                $config['password'],
                $config['db_name']
            );
            echo "✓ Conexión a la base de datos exitosa<br>";
        } catch (Exception $e) {
            echo "✗ Error de conexión: " . $e->getMessage() . "<br>";
        }
    }
} else {
    echo "✗ Clase MyConexion NO existe<br>";
}

require_once(__DIR__ . "/helper/Renderer.php");
if (class_exists('Renderer')) {
    echo "✓ Clase Renderer existe<br>";
} else {
    echo "✗ Clase Renderer NO existe<br>";
}

echo "<h2>5. Verificación de directorio actual:</h2>";
echo "DIR: " . __DIR__ . "<br>";

echo "<h2>6. Extensiones PHP habilitadas:</h2>";
echo "mysqli: " . (extension_loaded('mysqli') ? 'SÍ' : 'NO') . "<br>";
echo "session: " . (extension_loaded('session') ? 'SÍ' : 'NO') . "<br>";

?>

