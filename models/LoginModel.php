<?php

class LoginModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($user, $password)
    {
        $conn = $this->conexion->getConnection();
        $userEscaped = $conn->real_escape_string($user);

        $sql = "SELECT * FROM user WHERE username = '$userEscaped' AND email_verified = 1 LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            if (password_verify($password, $usuario['password'])) {
                return [$usuario];
            }
        }

        return [];
    }

    public function usuarioExiste($username)
    {
        $conn = $this->conexion->getConnection();
        $usernameEscaped = $conn->real_escape_string($username);
        $sql = "SELECT id FROM user WHERE username = '$usernameEscaped' LIMIT 1";
        $result = $this->conexion->query($sql);

        return !empty($result);
    }

    public function crearUsuario($username, $email, $password)
    {
        $conn = $this->conexion->getConnection();

        $usernameEscaped = $conn->real_escape_string($username);
        $emailEscaped = $conn->real_escape_string($email);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO user (username, email, password, email_verified, verify_token)
                VALUES ('$usernameEscaped', '$emailEscaped', '$passwordHash', 0, '$token')";

        if ($this->conexion->query($sql)) {
            return $token;
        }

        return false;
    }

    public function activarCuenta($token)
    {
        $conn = $this->conexion->getConnection();
        $tokenEscaped = $conn->real_escape_string($token);

        $usuario = $this->conexion->query("SELECT id FROM user WHERE verify_token = '$tokenEscaped' AND email_verified = 0");

        if (empty($usuario)) {
            return false;
        }

        $sql = "UPDATE user SET email_verified = 1, verify_token = NULL WHERE verify_token = '$tokenEscaped'";
        return $this->conexion->query($sql);
    }

    public function actualizarDatosExtra(
        $username,
        $name,
        $lastname,
        $birthYear,
        $gender,
        $profilePicture,
        $location,
        $lat,
        $lon,
        $countryCode
    ) {
        $conn = $this->conexion->getConnection();

        $usernameEsc = $conn->real_escape_string($username);
        $nameEsc = $conn->real_escape_string($name);
        $lastnameEsc = $lastname ? $conn->real_escape_string($lastname) : null;
        $birthYearEsc = $conn->real_escape_string($birthYear);
        $genderEsc = $conn->real_escape_string($gender);
        $picEsc = $conn->real_escape_string($profilePicture);
        $locEsc = $location ? $conn->real_escape_string($location) : null;
        $latEsc = $lat ? $conn->real_escape_string($lat) : null;
        $lonEsc = $lon ? $conn->real_escape_string($lon) : null;
        $countryEsc = $countryCode ? $conn->real_escape_string($countryCode) : null;

        // Valores por defecto
        $rol = "JUGADOR";
        $difficulty = "PRINCIPIANTE";
        $createdAt = date('Y-m-d H:i:s');

        $sql = "UPDATE user SET 
            name = '$nameEsc',
            lastname = " . ($lastnameEsc ? "'$lastnameEsc'" : "NULL") . ",
            birth_year = '$birthYearEsc',
            gender = '$genderEsc',
            profile_picture = '$picEsc',
            location = " . ($locEsc ? "'$locEsc'" : "NULL") . ",
            lat = " . ($latEsc ? "'$latEsc'" : "NULL") . ",
            lon = " . ($lonEsc ? "'$lonEsc'" : "NULL") . ",
            country_code = " . ($countryEsc ? "'$countryEsc'" : "NULL") . ",
            rol = '$rol',
            difficulty_level = '$difficulty',
            created_at = '$createdAt'
        WHERE username = '$usernameEsc'";

        return $conn->query($sql);
    }

    public function emailExiste($email)
    {
        $conn = $this->conexion->getConnection();
        $emailEscaped = $conn->real_escape_string($email);
        $sql = "SELECT id FROM user WHERE email = '$emailEscaped' LIMIT 1";
        $result = $this->conexion->query($sql);

        return !empty($result);
    }
}
