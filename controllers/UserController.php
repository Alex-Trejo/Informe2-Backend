<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT; // Asegúrate de incluir este namespace

use \Firebase\JWT\Key;
include_once 'models/User.php';
include_once 'config/database.php';  // Incluir la clase de conexión

class UserController {
    private $user;
    private $db;

    public function __construct() {
        // Crear una instancia de la base de datos
        $database = new Database();
        $this->db = $database->getConnection();  // Obtener la conexión
        $this->user = new User($this->db);  // Pasar la conexión al modelo User
    }

    // Registrar un nuevo usuario
    public function register($data) {
        // Asignar los datos recibidos
        $this->user->nombre = $data->nombre;
        $this->user->correo = $data->correo;
        $this->user->contrasena = $data->contrasena;

        // Intentar crear el usuario
        $response = $this->user->create();

        // Retornar la respuesta como JSON
        echo json_encode($response);
    }

    // Método de login
    public function login($data) {
        $this->user->correo = $data->correo;
        
        // Buscar al usuario por correo
        if ($this->user->findByEmail()) {
            // Verificar que la contraseña coincida
            if (password_verify($data->contrasena, $this->user->contrasena)) {
                // Crear el token JWT
                $issuedAt = time();
                $expirationTime = $issuedAt + 3600;  // El token expira en 1 hora desde su creación
                $payload = array(
                    "iat" => $issuedAt,
                    "exp" => $expirationTime,
                    "sub" => $this->user->id
                );

                // Clave secreta para firmar el token
                $key = "tu_clave_secreta";
                $jwt = JWT::encode($payload, $key, 'HS256'); // Especificar el algoritmo

                // Retornar el token JWT
                echo json_encode([
                    "message" => "Login exitoso",
                    "token" => $jwt
                ]);
            } else {
                echo json_encode(["message" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    }

    public function getUsers() {
        $stmt = $this->user->read(); // Llama al método 'read' del modelo User
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtén todos los resultados
    
        echo json_encode($users); // Devuelve los usuarios como JSON
    }  

    // Actualizar un usuario
    public function updateUser($data) {
        $this->user->id = $data->id;
        $this->user->nombre = $data->nombre;
        $this->user->correo = $data->correo;
    
        if ($this->user->update()) {
            echo json_encode(["message" => "Usuario actualizado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al actualizar el usuario"]);
        }
    }

    // Eliminar un usuario
    public function deleteUser($data) {
        // Validar que el ID esté presente
        if (isset($data->id)) {
            $this->user->id = $data->id;
    
            // Llamar al método delete del modelo User
            $response = $this->user->delete();
            echo json_encode($response);
        } else {
            echo json_encode(["message" => "ID no proporcionado"]);
        }
    }

    // Obtener un usuario por ID
public function getUser($id) {
    // Llamar al método del modelo para obtener los datos del usuario
    $user = $this->user->getUserById($id);

    // Verificar si se encontró el usuario
    if ($user) {
        echo json_encode($user);  // Devuelve los datos del usuario en formato JSON
    } else {
        echo json_encode(["message" => "Usuario no encontrado"]);
    }
}


// Dentro de la clase UserController
public function getUserById($id) {
    $this->user->id = $id;  // Asigna el ID al objeto del modelo

    // Llamar al método en el modelo para obtener los datos del usuario
    $user = $this->user->getUserById();  // Este método lo vamos a definir en el modelo

    if ($user) {
        echo json_encode($user);  // Devuelve los datos del usuario en formato JSON
    } else {
        echo json_encode(["message" => "Usuario no encontrado"]);
    }
}
// Método para obtener el usuario por el token
public function getUserByToken() {
    // Obtener el token del encabezado de la solicitud
    $token = getBearerToken();

    if ($token) {
        try {
            // Decodificar el token
            $key = "tu_clave_secreta";  // Usar la misma clave secreta para decodificar el token
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Obtener el ID del usuario desde el token
            $userId = $decoded->sub;

            // Buscar el usuario por ID
            $this->user->id = $userId;
            $user = $this->user->getUserById();

            // Verificar si se encontró el usuario
            if ($user) {
                echo json_encode($user);  // Devolver los datos del usuario
            } else {
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } catch (Exception $e) {
            // Si el token no es válido o ha expirado
            echo json_encode(["message" => "Token inválido o expirado"]);
        }
    } else {
        echo json_encode(["message" => "Token no proporcionado"]);
    }
}




}
?>
