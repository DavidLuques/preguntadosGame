<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores al usuario, solo en logs
ini_set('log_errors', 1);

session_start();

// Verificar que el archivo de configuración existe
$configPath = __DIR__ . '/config/config.ini';
if (!file_exists($configPath)) {
    die('Error: No se encontró el archivo de configuración. Asegúrate de crear config/config.ini con las credenciales de Hostinger.');
}

$config = parse_ini_file($configPath);
if ($config === false) {
    die('Error: No se pudo leer el archivo de configuración.');
}

include_once(__DIR__ . "/helper/MyConexion.php");

$conexion = new MyConexion(
    $config['server'],
    $config['username'],
    $config['password'],
    $config['db_name']
);

include_once(__DIR__ . "/helper/Renderer.php");
$renderer = new Renderer();

include_once(__DIR__ . "/controllers/LoginController.php");
$loginController = new LoginController($conexion, $renderer);

include_once(__DIR__ . "/controllers/JugadoresController.php");
$jugadoresController = new JugadoresController($conexion, $renderer);

$controllerParam = isset($_GET['controller']) ? $_GET['controller'] : null;
$methodParam = isset($_GET['method']) ? $_GET['method'] : null;

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
?>
