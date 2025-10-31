<?php

class Renderer {

    public function __constructor() {
        // Inicialización si es necesario
    }

    public function render($template, $data = []) {
        extract($data);
        include_once("views/partials/header.php");
        include_once("views/" . $template . "Vista.php");
        include_once("views/partials/footer.php");
    }
}