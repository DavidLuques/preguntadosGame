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

    public function registrarUsuario($username, $password)
    {
        $conn = $this->conexion->getConnection();
        
        // Escapar datos
        $usernameEscaped = $conn->real_escape_string($username);
        $passwordHash = hash('sha256', $password);
        
        // Valores por defecto
        $rol = 'JUGADOR';
        $authToken = '';
        $name = $usernameEscaped; // Usar el username como name por defecto
        $lastname = null;
        $birthYear = date('Y-m-d H:i:s', strtotime('1990-01-01'));
        $createdAt = date('Y-m-d H:i:s');
        $gender = 'Masculino';
        $email = $usernameEscaped . '@example.com'; // Email por defecto
        $country = null;
        $profilePicture = 'images/usuario.png';
        $totalScore = 0;
        $gamesPlayed = 0;
        $gamesWon = 0;
        $matchLost = 0;
        $difficultyLevel = null;
        $answeredQuestions = 0;
        
        $sql = "INSERT INTO user (username, password, rol, authToken, name, lastname, birth_year, created_at, gender, email, country, profile_picture, total_score, games_played, games_won, match_lost, difficulty_level, answered_questions) 
                VALUES ('$usernameEscaped', '$passwordHash', '$rol', '$authToken', '$name', " . 
                ($lastname ? "'$lastname'" : "NULL") . ", '$birthYear', '$createdAt', '$gender', '$email', " . 
                ($country ? "'$country'" : "NULL") . ", '$profilePicture', $totalScore, $gamesPlayed, $gamesWon, $matchLost, " . 
                ($difficultyLevel ? "'$difficultyLevel'" : "NULL") . ", $answeredQuestions)";
        
        $result = $conn->query($sql);
        
        if ($result) {
            return true;
        } else {
            error_log("Error al registrar usuario: " . $conn->error);
            return false;
        }
    }
}