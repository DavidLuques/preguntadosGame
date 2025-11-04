<?php
// TEMPORAL: Activar errores para diagnóstico
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

// Verificar que el archivo de configuración existe
$configPath = __DIR__ . '/config/config.ini';
if (!file_exists($configPath)) {
    die('Error: No se encontró el archivo de configuración. Asegúrate de crear config/config.ini con las credenciales de Hostinger.');
}

$config = parse_ini_file($configPath);
if ($config === false) {
    die('Error: No se pudo leer el archivo de configuración.');
}

// Verificar que todas las claves necesarias existen
$requiredKeys = ['server', 'username', 'password', 'db_name'];
foreach ($requiredKeys as $key) {
    if (!isset($config[$key])) {
        die('Error: Falta la clave "' . $key . '" en config/config.ini');
    }
}

try {
    include_once(__DIR__ . "/helper/MyConexion.php");
    
    if (!class_exists('MyConexion')) {
        die('Error: No se pudo cargar la clase MyConexion');
    }
    
    $conexion = new MyConexion(
        $config['server'],
        $config['username'],
        $config['password'],
        $config['db_name']
    );
} catch (Exception $e) {
    die('Error de conexión: ' . $e->getMessage() . '<br>Verifica las credenciales en config/config.ini');
}

try {
    include_once(__DIR__ . "/helper/Renderer.php");
    if (!class_exists('Renderer')) {
        die('Error: No se pudo cargar la clase Renderer');
    }
    $renderer = new Renderer();
    
    include_once(__DIR__ . "/controllers/LoginController.php");
    if (!class_exists('LoginController')) {
        die('Error: No se pudo cargar la clase LoginController');
    }
    $loginController = new LoginController($conexion, $renderer);
    
    include_once(__DIR__ . "/controllers/JugadoresController.php");
    if (!class_exists('JugadoresController')) {
        die('Error: No se pudo cargar la clase JugadoresController');
    }
    $jugadoresController = new JugadoresController($conexion, $renderer);
} catch (Exception $e) {
    die('Error cargando controladores: ' . $e->getMessage());
}

$controllerParam = isset($_GET['controller']) ? $_GET['controller'] : null;
$methodParam = isset($_GET['method']) ? $_GET['method'] : null;

try {
    switch ($controllerParam) {
        case 'login':
            switch ($methodParam) {
                case 'login':
                    $loginController->login();
                    break;
                case 'registro':
                    $loginController->registro();
                    break;
                default:
                    $loginController->logout();
                    break;
            }
            break;
        case 'jugadores':
            switch ($methodParam) {
                case 'listadoJugadores':
                    $jugadoresController->listadoJugadores();
                    break;
                default:
                    $jugadoresController->partida();
                    break;
            }
            break;
        default:
            $jugadoresController->listadoJugadores();
            break;
    }
} catch (Exception $e) {
    die('Error ejecutando controlador: ' . $e->getMessage() . '<br>Archivo: ' . $e->getFile() . '<br>Línea: ' . $e->getLine());
} catch (Error $e) {
    die('Error fatal: ' . $e->getMessage() . '<br>Archivo: ' . $e->getFile() . '<br>Línea: ' . $e->getLine());
}
?>
