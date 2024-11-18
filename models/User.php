<?php
class User {
    private $conn;
    private $table_name = "usuarios"; // nombre de la tabla

    public $id;
    public $nombre;
    public $correo;
    public $contrasena;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo usuario
    public function create() {
        // Asegúrate de que los datos no estén vacíos
        if (empty($this->nombre) || empty($this->correo) || empty($this->contrasena)) {
            return array("message" => "Datos incompletos");  // Devuelvo un mensaje en formato JSON
        }
    
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, correo=:correo, contrasena=:contrasena";
        $stmt = $this->conn->prepare($query);
    
        // Limpiar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->contrasena = htmlspecialchars(strip_tags($this->contrasena));
    
        // Cifrar la contraseña
        $hashed_contrasena = password_hash($this->contrasena, PASSWORD_BCRYPT);
    
        // Vincular los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":contrasena", $hashed_contrasena);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return array("message" => "Usuario creado correctamente");
        }
    
        return array("message" => "Error al crear el usuario");
    }

    // Buscar un usuario por correo (para login)
    public function findByEmail() {
        $query = "SELECT id, nombre, correo, contrasena FROM " . $this->table_name . " WHERE correo = :correo LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->contrasena = $row['contrasena'];

            return true;
        }

        return false;
    }

    // Actualizar un usuario
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, correo = :correo 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->id = htmlspecialchars(strip_tags($this->id));
    
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":id", $this->id);
    
        return $stmt->execute();
    }

    // Eliminar un usuario
    public function delete() {
        // Asegúrate de que el ID sea válido
        if (empty($this->id) || !is_numeric($this->id)) {
            return array("message" => "ID inválido");
        }
    
        // Prepara la consulta SQL
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        // Limpia y asigna el ID
        $this->id = htmlspecialchars(strip_tags((string)$this->id)); // Asegurar que sea un string
        $stmt->bindParam(":id", $this->id);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return array("message" => "Usuario eliminado correctamente");
        }
    
        return array("message" => "Error al eliminar el usuario");
    }
    



    // Recuperar todos los usuarios (CRUD)
    public function read() {
        $query = "SELECT id, nombre, correo FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Dentro de la clase User
public function getUserById() {
    $query = "SELECT id, nombre, correo FROM " . $this->table_name . " WHERE id = :id LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $this->id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;  // Devuelve los datos del usuario en forma de array
    }

    return null;  // Si no se encuentra el usuario
}


   
    
}



?>
