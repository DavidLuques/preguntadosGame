<?php

require_once __DIR__ . '/../vendor/autoload.php';
class Renderer
{

    private $mustache;
    private $templatePath;

    public function __construct()
    {
        $this->templatePath = __DIR__ . '/../views/templates';

        if (!file_exists($this->templatePath)) {
            mkdir($this->templatePath, 0755, true);
        }
        if (!file_exists($this->templatePath . '/partials')) {
            mkdir($this->templatePath . '/partials', 0755, true);
        }

        if (!class_exists('Mustache_Engine')) {
            die('Error: Mustache no está instalado. Ejecuta: composer install');
        }

        $loader = new Mustache_Loader_FilesystemLoader($this->templatePath, ['extension' => '.mustache']);

        $this->mustache = new Mustache_Engine([
            'loader' => $loader,
            'partials_loader' => $loader, 
            'escape' => function ($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            },
            'strict_callables' => false
        ]);
    }

    public function render($template, $data = [])
    {
        try {
            $data['usuario'] = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : false;
            $data['currentYear'] = date("Y");

            if (!isset($data['jugadores'])) {
                $data['jugadores'] = [];
            }

            if (isset($data['jugadores']) && is_array($data['jugadores']) && !empty($data['jugadores'])) {
                $data['jugadores'] = array_map(function ($jugador) {
                    $defaults = [
                        'id' => '',
                        'usuario' => '',
                        'pais' => '',
                        'puntajeTotal' => '',
                        'fotoPerfil' => '',
                        'posicion' => ''
                    ];

                    $result = [];
                    foreach ($defaults as $key => $defaultValue) {
                        $result[$key] = isset($jugador[$key]) && $jugador[$key] !== null
                            ? (string)$jugador[$key]
                            : $defaultValue;
                    }

                    return $result;
                }, $data['jugadores']);
            }

            $templateFile = $this->templatePath . '/' . $template . '.mustache';
            if (!file_exists($templateFile)) {
                die('Error: Template no encontrado: ' . $template . '.mustache en ' . $this->templatePath);
            }

            $headerFile = $this->templatePath . '/partials/header.mustache';
            $footerFile = $this->templatePath . '/partials/footer.mustache';
            if (!file_exists($headerFile)) {
                die('Error: Partial header no encontrado en: ' . $headerFile);
            }
            if (!file_exists($footerFile)) {
                die('Error: Partial footer no encontrado en: ' . $footerFile);
            }

            $templateContent = $this->mustache->render($template, $data);
            echo $templateContent;
        } catch (Exception $e) {
            error_log("Error en Renderer: " . $e->getMessage());
            die('Error renderizando plantilla: ' . htmlspecialchars($e->getMessage()));
        }
    }
}
