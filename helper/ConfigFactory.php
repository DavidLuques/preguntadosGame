<?php

class ConfigFactory
{

    public function __construct()
    {
        // Constructor vacío o inicialización si es necesario
    }

    public function create($configName)
    {
        $configPath = __DIR__ . '/config/config.ini';
        $config = parse_ini_file($configPath);

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
    }
}
