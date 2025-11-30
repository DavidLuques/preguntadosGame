<?php

class PerfilModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = '$id'";
        $result = $this->database->query($sql);
        return $result ? $result[0] : null;
    }

    public function updateUser($id, $name, $lastname, $birthYear, $gender, $email, $location, $lat, $lon)
    {
        $conn = $this->database->getConnection();

        $location = $conn->real_escape_string($location);
        $lat = floatval($lat);
        $lon = floatval($lon);

        $sql = "UPDATE user SET 
        name = '$name',
        lastname = '$lastname',
        birth_year = '$birthYear',
        gender = '$gender',
        email = '$email',
        location = '$location',
        lat = '$lat',
        lon = '$lon'
        WHERE id = '$id'";

        $this->database->query($sql);
    }

    public function verifyPassword($id, $password)
    {
        $user = $this->getUserById($id);
        if (!$user) return false;

        // Check hash first
        if (hash('sha256', $password) === $user['password']) {
            return true;
        }
        // Fallback for plain text (legacy/dev)
        if ($password === $user['password']) {
            return true;
        }

        return false;
    }

    public function updatePassword($id, $newPassword)
    {
        $hash = hash('sha256', $newPassword);
        $sql = "UPDATE user SET password = '$hash' WHERE id = '$id'";
        $this->database->query($sql);
    }

    public function updateProfilePicture($id, $path)
    {
        $sql = "UPDATE user SET profile_picture = '$path' WHERE id = '$id'";
        $this->database->query($sql);
    }
}
