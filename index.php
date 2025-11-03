<?php
session_start();

$config = parse_ini_file('config/config.ini');
include_once("helper/MyConexion.php");

$conexion = new MyConexion(
    $config['server'],
    $config['username'],
    $config['password'],
    $config['db_name']
);

include_once("helper/Renderer.php");
$renderer = new Renderer();

include_once("controllers/LoginController.php");
$loginController = new LoginController($conexion, $renderer);

include_once("controllers/JugadoresController.php");
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
