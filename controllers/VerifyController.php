<?php

class VerifyController {
    private $model;
    private $renderer;

    public function __construct($model, $renderer) {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base() {
        if (!isset($_GET['token'])) {
            die("Token inválido");
        }

        $token = $_GET['token'];
        $resultado = $this->model->activarCuenta($token);

        if ($resultado) {
            $this->renderer->render("mensaje", [
                "mensaje" => "Tu cuenta fue verificada correctamente. Ya podés iniciar sesión."
            ]);
        } else {
            $this->renderer->render("mensaje", [
                "mensaje" => "El enlace de verificación es inválido o ya fue utilizado."
            ]);
        }
    }
}