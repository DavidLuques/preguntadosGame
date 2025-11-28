<?php
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

            // Si el array no está vacío, el usuario existe
            if (!empty($resultado)) {
                $usuario = $resultado[0];
                $_SESSION["usuario"] = $usuario["username"];
                $_SESSION["usuario_id"] = isset($usuario["id"]) ? intval($usuario["id"]) : null;
                $_SESSION["rol"] = $usuario["rol"];

                if ($usuario["rol"] === 'editor') {
                    header("Location: /editor/dashboard");
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
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validar campos requeridos
            $requiredFields = ['nombre', 'password', 'password_confirm', 'name', 'lastname', 'birth_year', 'gender', 'email', 'country'];
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
            $country = trim($_POST["country"]);

            // Validar confirmación de contraseña
            if ($password !== $passwordConfirm) {
                $this->renderer->render("registro", ["error" => "Las contraseñas no coinciden"]);
                return;
            }

            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->renderer->render("registro", ["error" => "El email no es válido"]);
                return;
            }

            // Verificar si el usuario ya existe
            if ($this->model->usuarioExiste($username)) {
                $this->renderer->render("registro", ["error" => "El nombre de usuario ya está en uso"]);
                return;
            }

            // Manejar subida de imagen de perfil
            $profilePicture = 'images/usuario.png'; // Valor por defecto
            
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_picture'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                // Validar tipo de archivo
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->renderer->render("registro", ["error" => "El archivo debe ser una imagen (JPG, PNG o GIF)"]);
                    return;
                }
                
                // Validar tamaño
                if ($file['size'] > $maxSize) {
                    $this->renderer->render("registro", ["error" => "La imagen es demasiado grande. Máximo 5MB"]);
                    return;
                }
                
                // Generar nombre único para el archivo
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = 'profile_' . $username . '_' . time() . '.' . $extension;
                $uploadPath = __DIR__ . '/../images/' . $newFileName;
                
                // Crear directorio si no existe
                $imagesDir = __DIR__ . '/../images';
                if (!is_dir($imagesDir)) {
                    mkdir($imagesDir, 0755, true);
                }
                
                // Mover archivo
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $profilePicture = 'images/' . $newFileName;
                } else {
                    $this->renderer->render("registro", ["error" => "Error al subir la imagen. Por favor, intentá nuevamente"]);
                    return;
                }
            }

            // Convertir fecha de nacimiento al formato requerido
            $birthYearFormatted = date('Y-m-d H:i:s', strtotime($birthYear));

            // Registrar el usuario
            if ($this->model->registrarUsuario($username, $password, $name, $lastname, $birthYearFormatted, $gender, $email, $country, $profilePicture)) {
                // Mostrar mensaje de éxito
                $this->renderer->render("registro", ["success" => true, "mensaje" => "¡Éxito! Usuario registrado correctamente"]);
            } else {
                $this->renderer->render("registro", ["error" => "Error al registrar el usuario. Por favor, intentá nuevamente"]);
            }
        } else {
            $success = isset($_GET['success']) && $_GET['success'] === 'registro';
            $this->renderer->render("registro", ['success' => $success]);
        }
    }
}
