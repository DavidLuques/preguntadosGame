<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once(__DIR__ . "/../models/LoginModel.php");

class LoginController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base()
    {
        $this->login();
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_POST["nombre"]) || !isset($_POST["password"])) {
                $this->renderer->render("login", ["error" => "Faltan datos de inicio de sesión"]);
                return;
            }

            $resultado = $this->model->getUserWith($_POST["nombre"], $_POST["password"]);

            if (!empty($resultado)) {
                $usuario = $resultado[0];
                $_SESSION["usuario"] = $usuario["username"];
                $_SESSION["usuario_id"] = isset($usuario["id"]) ? intval($usuario["id"]) : null;
                $_SESSION["rol"] = $usuario["rol"];

                if ($usuario["rol"] === 'editor') {
                    header("Location: /editor/dashboard");
                } elseif ($usuario["rol"] === 'ADMIN') {
                    header("Location: /admin/dashboard");
                } else {
                    header("Location: /");
                }
                exit();
            } else {
                $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            }
        } else {
            $this->renderer->render("login");
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /");
        exit();
    }

    public function registro()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $success = isset($_GET['success']) && $_GET['success'] === 'registro';
            $this->renderer->render("registro", ['success' => $success]);
            return;
        }

        $requiredFields = ['nombre', 'password', 'password_confirm', 'name', 'lastname', 'birth_year', 'gender', 'email'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $this->renderer->render("registro", ["error" => "Todos los campos marcados con * son obligatorios"]);
                return;
            }
        }

        $username = trim($_POST["nombre"]);
        $password = $_POST["password"];
        $passwordConfirm = $_POST["password_confirm"];
        $name = trim($_POST["name"]);
        $lastname = trim($_POST["lastname"]);
        $birthYear = $_POST["birth_year"];
        $gender = $_POST["gender"];
        $email = trim($_POST["email"]);
        $location = trim($_POST["location"]);
        $lat = $_POST["lat"] ?? "";
        $lon = $_POST["lon"] ?? "";
        $countryCode = $_POST["country_code"] ?? "";

        if ($password !== $passwordConfirm) {
            $this->renderer->render("registro", ["error" => "Las contraseñas no coinciden"]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderer->render("registro", ["error" => "El email no es válido"]);
            return;
        }

        if ($this->model->usuarioExiste($username)) {
            $this->renderer->render("registro", ["error" => "El nombre de usuario ya está en uso"]);
            return;
        }

        if ($this->model->emailExiste($email)) {
            $this->renderer->render("registro", ["error" => "El email ya está registrado"]);
            return;
        }

        $token = $this->model->crearUsuario($username, $email, $password);

        if (!$token) {
            $this->renderer->render("registro", ["error" => "Error al registrar"]);
            return;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'smarcheschi97@gmail.com';
            $mail->Password   = 'dslz pmll imju uqhm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('smarcheschi97@gmail.com', 'Preguntados Game');
            $mail->addAddress($email);

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            $link = $protocol . $domainName . "/verify?token=" . urlencode($token);
            $mail->isHTML(true);
            $mail->Subject = "ACTIVACION DE CUENTA";
            $mail->Body = "
                <h2>Bienvenido $username</h2>
                <p>Gracias por registrarte en nuestro juego.</p>
                <p>Hace clic en el siguiente enlace para activar tu cuenta:</p>
                <a href='$link'>Verificar mi cuenta</a>
                <br><br>
                <p>Si no hiciste este registro, ignora este correo.</p>
                <p>Saludos, de parte del EQUIPO 9</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            $this->renderer->render("registro", [
                "error" => "No se pudo enviar el email: {$mail->ErrorInfo}"
            ]);
            return;
        }

        $profilePicture = 'images/usuario.png';

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowedTypes)) {
                $this->renderer->render("registro", ["error" => "La imagen debe ser JPG, PNG o GIF"]);
                return;
            }

            if ($file['size'] > $maxSize) {
                $this->renderer->render("registro", ["error" => "La imagen es demasiado grande. Máximo 5MB"]);
                return;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'profile_' . $username . '_' . time() . '.' . $extension;
            $path = __DIR__ . '/../images/' . $newFileName;

            if (!is_dir(__DIR__ . '/../images')) {
                mkdir(__DIR__ . '/../images', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $path)) {
                $profilePicture = 'images/' . $newFileName;
            }
        }

        $birthYearFormatted = date('Y-m-d H:i:s', strtotime($birthYear));

        $this->model->actualizarDatosExtra(
            $username,
            $name,
            $lastname,
            $birthYearFormatted,
            $gender,
            $profilePicture,
            $location,
            $lat,
            $lon,
            $countryCode
        );

        $this->renderer->render("registro", [
            "success" => "Registrado correctamente. Revisá tu email para activar tu usuario."
        ]);
    }
}
