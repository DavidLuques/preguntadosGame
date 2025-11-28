<?php

class PerfilController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }
    }

    public function perfil()
    {
        $this->checkLoggedIn();
        $userId = $_SESSION['usuario_id'];
        $user = $this->model->getUserById($userId);
        
        // Format birth_year for input date (YYYY-MM-DD)
        if (isset($user['birth_year'])) {
            $user['birth_year_formatted'] = date('Y-m-d', strtotime($user['birth_year']));
        }

        $success = isset($_GET['success']) ? $_GET['success'] : null;

        // Generate QR Code URL for logged-in user
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $host = $_SERVER['HTTP_HOST'];
        $profileUrl = $protocol . "://" . $host . "/perfil/ver?id=" . $userId;
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($profileUrl);
        
        $this->renderer->render("perfil/perfil", [
            "user" => $user,
            "qr_code_url" => $qrCodeUrl,
            "success" => $success,
            "success_data_updated" => $success === 'data_updated',
            "success_password_updated" => $success === 'password_updated',
            "success_picture_updated" => $success === 'picture_updated',
            "error" => isset($_GET['error']) ? urldecode($_GET['error']) : null
        ]);
    }

    public function updateData()
    {
        $this->checkLoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['usuario_id'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $birthYear = $_POST['birth_year'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $country = $_POST['country'];

            $this->model->updateUser($userId, $name, $lastname, $birthYear, $gender, $email, $country);
            header("Location: /perfil/perfil?success=data_updated");
        }
    }

    public function changePassword()
    {
        $this->checkLoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['usuario_id'];
            $currentPass = $_POST['current_password'];
            $newPass = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];

            if ($newPass !== $confirmPass) {
                header("Location: /perfil/perfil?error=" . urlencode("Las contraseñas nuevas no coinciden"));
                return;
            }

            if ($this->model->verifyPassword($userId, $currentPass)) {
                $this->model->updatePassword($userId, $newPass);
                header("Location: /perfil/perfil?success=password_updated");
            } else {
                header("Location: /perfil/perfil?error=" . urlencode("La contraseña actual es incorrecta"));
            }
        }
    }

    public function changePicture()
    {
        $this->checkLoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
            $userId = $_SESSION['usuario_id'];
            $file = $_FILES['profile_picture'];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowedTypes)) {
                    header("Location: /perfil/perfil?error=" . urlencode("Formato de imagen no válido"));
                    return;
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = 'profile_' . $_SESSION['usuario'] . '_' . time() . '.' . $extension;
                $uploadPath = __DIR__ . '/../images/' . $newFileName;
                
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $dbPath = 'images/' . $newFileName;
                    $this->model->updateProfilePicture($userId, $dbPath);
                    header("Location: /perfil/perfil?success=picture_updated");
                } else {
                    header("Location: /perfil/perfil?error=" . urlencode("Error al subir la imagen"));
                }
            } else {
                header("Location: /perfil/perfil?error=" . urlencode("Error en la subida del archivo"));
            }
        }
    }

    public function ver()
    {
        if (!isset($_GET['id'])) {
            header("Location: /");
            exit();
        }

        $userId = $_GET['id'];
        $user = $this->model->getUserById($userId);

        if (!$user) {
            die("Usuario no encontrado");
        }

        // goqr.me API uso aca
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $host = $_SERVER['HTTP_HOST'];
        $profileUrl = $protocol . "://" . $host . "/perfil/ver?id=" . $user['id'];
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($profileUrl);
        echo($qrCodeUrl);
        $this->renderer->render("perfil/usuario", [
            "user" => $user,
            "qr_code_url" => $qrCodeUrl
        ]);
    }
}
