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
        // Usar un solo loader para templates y partials
        $loader = new Mustache_Loader_FilesystemLoader($this->templatePath, ['extension' => '.mustache']);
        
        $this->mustache = new Mustache_Engine([
            'loader' => $loader,
            'partials_loader' => $loader, // Mismo loader para partials
            'escape' => function($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            },
            'strict_callables' => false
        ]);
    }

    public function render($template, $data = []) {
        try {
            // Agregar datos globales que necesitan todas las plantillas
            $data['usuario'] = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : false;
            $data['currentYear'] = date("Y");
            
            // Asegurar que los arrays estén siempre definidos
            if (!isset($data['jugadores'])) {
                $data['jugadores'] = [];
            }
            
            // Convertir arrays asociativos a formato compatible con Mustache
            if (isset($data['jugadores']) && is_array($data['jugadores']) && !empty($data['jugadores'])) {
                $data['jugadores'] = array_map(function($jugador) {
                    // Asegurar que todos los campos existan
                    return array_merge([
                        'id' => '',
                        'usuario' => '',
                        'nombre' => '',
                        'apellido' => '',
                        'mail' => '',
                        'pais' => '',
                        'ciudad' => '',
                        'sexo' => '',
                        'nacimiento' => '',
                        'fechaAlta' => '',
                        'rol' => '',
                        'puntajeTotal' => '',
                        'partidasJugadas' => '',
                        'partidasGanadas' => '',
                        'partidasPerdidas' => '',
                        'nivelDificultad' => '',
                        'fotoPerfil' => ''
                    ], $jugador);
                }, $data['jugadores']);
            }
            
            // Verificar que el template existe
            $templateFile = $this->templatePath . '/' . $template . '.mustache';
            if (!file_exists($templateFile)) {
                die('Error: Template no encontrado: ' . $template . '.mustache en ' . $this->templatePath);
            }
            
            // Verificar que los partials existen
            $headerFile = $this->templatePath . '/partials/header.mustache';
            $footerFile = $this->templatePath . '/partials/footer.mustache';
            if (!file_exists($headerFile)) {
                die('Error: Partial header no encontrado en: ' . $headerFile);
            }
            if (!file_exists($footerFile)) {
                die('Error: Partial footer no encontrado en: ' . $footerFile);
            }
            
            // Renderizar la plantilla
            $templateContent = $this->mustache->render($template, $data);
            echo $templateContent;
            
        } catch (Exception $e) {
            error_log("Error en Renderer: " . $e->getMessage());
            die('Error renderizando plantilla: ' . htmlspecialchars($e->getMessage()));
        }
    }
}