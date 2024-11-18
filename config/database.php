<?php
class Database {
    private $host = "localhost";
    private $db_name = "usuarios_db"; // Cambia esto por tu base de datos
    private $username = "root"; // Cambia esto si tu usuario es diferente
    private $password = ""; // Cambia esto si tu contraseña es diferente
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
