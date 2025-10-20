<?php
class Database {
    private $host = "u625329211_preguntados";
    private $db_name = "u625329211_preguntados";
    private $username = "root";
    private $password = "+MpWww?d$5P";
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }
}