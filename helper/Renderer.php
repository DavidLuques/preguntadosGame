<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Renderer {

    private $mustache;
    private $templatePath;

    public function __construct() {
        $this->templatePath = __DIR__ . '/../views/templates';
        
        // Crear directorio de templates si no existe
        if (!file_exists($this->templatePath)) {
            mkdir($this->templatePath, 0755, true);
        }
        if (!file_exists($this->templatePath . '/partials')) {
            mkdir($this->templatePath . '/partials', 0755, true);
        }
        
        // Verificar que Composer esté instalado
        if (!class_exists('Mustache_Engine')) {
            die('Error: Mustache no está instalado. Ejecuta: composer install');
        }
        
        // Configurar Mustache
        $this->mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader($this->templatePath, ['extension' => '.mustache']),
            'partials_loader' => new Mustache_Loader_FilesystemLoader($this->templatePath . '/partials', ['extension' => '.mustache']),
            'escape' => function($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        ]);
    }

    public function render($template, $data = []) {
        // Agregar datos globales que necesitan todas las plantillas
        $data['usuario'] = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;
        $data['currentYear'] = date("Y");
        
        // Renderizar la plantilla
        $templateContent = $this->mustache->render($template, $data);
        echo $templateContent;
    }
}