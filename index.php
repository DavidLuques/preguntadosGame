<?php
session_start();

$config = parse_ini_file('cconfig/config.ini');
include_once("helpers/MyConexion.php");
$conexion = new MyConexion(
    $config['server'],
    $config['username'],
    $config['password'],
    $config['db_name']
);

$request = $_GET['request'];

switch ($request) {
    case 'login':
        include_once("controllers/login.php");
        break;
    case 'register':
        include_once("controllers/registro.php");
        break;
    default:
        include_once("views/vista_principal.php");
        break;
}
?>
