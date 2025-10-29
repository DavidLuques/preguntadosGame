<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nombre;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO " . $this->table . " (nombre, password)
                  VALUES (:nombre, :password)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":password", $this->password);

        return $stmt->execute();
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($this->password, $usuario['password'])) {
            return $usuario;
        }

        return false;
    }
}