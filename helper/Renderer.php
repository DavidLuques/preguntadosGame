<?php

class Renderer {

    public function __construct() {
        // Inicialización si es necesario
    }

    public function render($template, $data = []) {
        extract($data);
        $basePath = __DIR__ . '/../views';
        include_once($basePath . "/partials/header.php");
        include_once($basePath . "/" . $template . "Vista.php");
        include_once($basePath . "/partials/footer.php");
    }
}