<?php

include_once(__DIR__ . "/MyConexion.php");
include_once(__DIR__ . "/Renderer.php");
include_once(__DIR__ . "/Router.php");
include_once(__DIR__ . "/../controllers/LoginController.php");
include_once(__DIR__ . "/../controllers/JugadoresController.php");
include_once(__DIR__ . "/../controllers/PartidaController.php");
include_once(__DIR__ . "/../models/LoginModel.php");
include_once(__DIR__ . "/../models/JugadoresModel.php");
include_once(__DIR__ . "/../models/PartidaModel.php");
include_once(__DIR__ . "/../controllers/EditorController.php");
include_once(__DIR__ . "/../models/EditorModel.php");
include_once(__DIR__ . "/../models/PerfilModel.php");
include_once(__DIR__ . "/../controllers/PerfilController.php");
include_once(__DIR__ . "/../models/AdminModel.php");
include_once(__DIR__ . "/../controllers/AdminController.php");

class ConfigFactory
{
    private $config;
    private $objetos;
    private $conexion;
    private $renderer;

    public function __construct()
    {
        $this->config = parse_ini_file(__DIR__ . '/../config/config.ini');

        $this->conexion = new MyConexion(
            $this->config['server'],
            $this->config['username'],
            $this->config['password'],
            $this->config['db_name']
        );

        $this->renderer = new Renderer();

        $this->objetos["LoginModel"] = new LoginModel($this->conexion);
        $this->objetos["LoginController"] = new LoginController(
            $this->objetos["LoginModel"],
            $this->renderer
        );

        $this->objetos["JugadoresModel"] = new JugadoresModel($this->conexion);
        $this->objetos["JugadoresController"] = new JugadoresController(
            $this->objetos["JugadoresModel"],
            $this->renderer
        );

        $this->objetos["PartidaModel"] = new PartidaModel($this->conexion);
        $this->objetos["PartidaController"] = new PartidaController(
            $this->objetos["PartidaModel"],
            $this->renderer
        );

        $this->objetos["EditorModel"] = new EditorModel($this->conexion);
        $this->objetos["EditorController"] = new EditorController(
            $this->objetos["EditorModel"],
            $this->renderer
        );

        $this->objetos["PerfilModel"] = new PerfilModel($this->conexion);
        $this->objetos["PerfilController"] = new PerfilController(
            $this->objetos["PerfilModel"],
            $this->renderer
        );

        $this->objetos["AdminModel"] = new AdminModel($this->conexion);
        $this->objetos["AdminController"] = new AdminController(
            $this->objetos["AdminModel"],
            $this->renderer
        );

        $this->objetos["Router"] = new Router(
            $this,
            'JugadoresController',
            'base'
        );
    }

    public function get($objectName)
    {
        return $this->objetos[$objectName];
    }
}