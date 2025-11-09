<?php

include_once(__DIR__ . "/MyConexion.php");
include_once(__DIR__ . "/Renderer.php");
include_once(__DIR__ . "/Router.php");
include_once(__DIR__ . "/../controllers/LoginController.php");
include_once(__DIR__ . "/../controllers/JugadoresController.php");
include_once(__DIR__ . "/../models/LoginModel.php");
include_once(__DIR__ . "/../models/JugadoresModel.php");

class ConfigFactory
{
    private $config;
    private $objetos;
    private $conexion;
    private $renderer;

    public function __construct()
    {
        $this->config = parse_ini_file(__DIR__ . '/../config/config.ini');

        $this->objetos["MyConexion"] = new MyConexion(
            $this->config['server'],
            $this->config['username'],
            $this->config['password'],
            $this->config['db_name']
        );

        $this->objetos["Renderer"] = new Renderer();

        $this->objetos["LoginModel"] = new LoginModel(
            $this->objetos["MyConexion"]
        );

        $this->objetos["LoginController"] = new LoginController(
            $this->objetos["LoginModel"],
            $this->objetos["Renderer"]
        );

        $this->objetos["JugadoresController"] = new JugadoresController(
            $this->objetos["MyConexion"],
            $this->objetos["Renderer"]
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
