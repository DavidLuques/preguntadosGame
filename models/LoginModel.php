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
        $passwordHash = hash('sha256', $password);
        
        $sql = "SELECT * FROM user WHERE username = '$userEscaped' AND password = '$passwordHash'";
        $result = $this->conexion->query($sql);

        // Fallback: Check for plain text password (for dev/legacy users like 'editor')
        if (empty($result)) {
            $sqlPlain = "SELECT * FROM user WHERE username = '$userEscaped' AND password = '$password'";
            $result = $this->conexion->query($sqlPlain);
        }

        return $result ?? [];
    }

    public function usuarioExiste($username)
    {
        $conn = $this->conexion->getConnection();
        $usernameEscaped = $conn->real_escape_string($username);
        $sql = "SELECT id FROM user WHERE username = '$usernameEscaped' LIMIT 1";
        $result = $this->conexion->query($sql);
        
        return !empty($result);
    }

    public function registrarUsuario($username, $password, $name, $lastname, $birthYear, $gender, $email, $country, $profilePicture)
    {
        $conn = $this->conexion->getConnection();
        
        // Escapar datos
        $usernameEscaped = $conn->real_escape_string($username);
        $passwordHash = hash('sha256', $password);
        $nameEscaped = $conn->real_escape_string($name);
        $lastnameEscaped = $lastname ? $conn->real_escape_string($lastname) : null;
        $birthYearEscaped = $conn->real_escape_string($birthYear);
        $genderEscaped = $conn->real_escape_string($gender);
        $emailEscaped = $conn->real_escape_string($email);
        $countryEscaped = $country ? $conn->real_escape_string($country) : null;
        $profilePictureEscaped = $conn->real_escape_string($profilePicture);
        
        // Valores por defecto
        $rol = 'JUGADOR';
        $authToken = '';
        $createdAt = date('Y-m-d H:i:s');
        $totalScore = 0;
        $gamesPlayed = 0;
        $gamesWon = 0;
        $matchLost = 0;
        $difficultyLevel = 'Principiante'; // Nivel inicial: Fácil
        $answeredQuestions = 0;
        
        $sql = "INSERT INTO user (username, password, rol, authToken, name, lastname, birth_year, created_at, gender, email, country, profile_picture, total_score, games_played, games_won, match_lost, difficulty_level, answered_questions) 
                VALUES ('$usernameEscaped', '$passwordHash', '$rol', '$authToken', '$nameEscaped', " . 
                ($lastnameEscaped ? "'$lastnameEscaped'" : "NULL") . ", '$birthYearEscaped', '$createdAt', '$genderEscaped', '$emailEscaped', " . 
                ($countryEscaped ? "'$countryEscaped'" : "NULL") . ", '$profilePictureEscaped', $totalScore, $gamesPlayed, $gamesWon, $matchLost, " . 
                "'$difficultyLevel', $answeredQuestions)";
        
        $result = $conn->query($sql);
        
        if ($result) {
            return true;
        } else {
            error_log("Error al registrar usuario: " . $conn->error);
            return false;
        }
    }
}