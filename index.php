<?php
header("Access-Control-Allow-Origin: *"); // Permitir solicitudes de todos los orígenes. O puedes usar "http://localhost:61142" para especificar solo tu frontend.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos


// index.php o el archivo que maneje las solicitudes de autenticación
function getBearerToken() {
    // Obtiene los encabezados de la solicitud
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $matches = [];
        // Extrae el token de la cabecera 'Authorization'
        if (preg_match('/Bearer (.+)/', $headers['Authorization'], $matches)) {
            return $matches[1];  // Devuelve el token
        }
    }
    return null;  // Si no se encuentra el token
}


// Si es una solicitud OPTIONS (preflight), responder con código 200 y salir
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


include_once 'controllers/UserController.php';
include_once 'authenticate.php';

header("Content-Type: application/json");

$action = isset($_GET['action']) ? $_GET['action'] : die();

// Recibir el cuerpo de la solicitud POST
$data = json_decode(file_get_contents("php://input"));

$userController = new UserController();

if ($action == 'register') {
    // Validar que los datos estén presentes
    if (isset($data->nombre) && isset($data->correo) && isset($data->contrasena)) {
        // Llamar al método de registro
        $userController->register($data);
    } else {
        echo json_encode(["message" => "Datos incompletos"]);
    }
} elseif ($action == 'login') {
    // Validar que los datos estén presentes
    if (isset($data->correo) && isset($data->contrasena)) {
        // Llamar al método de login
        $userController->login($data);
    } else {
        echo json_encode(["message" => "Datos incompletos"]);
    }


} elseif ($action == 'users') {
    if (authenticate()) {
        $userController->getUsers();
    }
}elseif ($action == 'user') {
    if (authenticate()) {
        if (isset($data->id)) {
            $userController->getUser($data->id);  // Llama al método para obtener un solo usuario
        } else {
            echo json_encode(["message" => "ID del usuario no proporcionado"]);
        }
    } else {
        echo json_encode(["message" => "Autenticación fallida"]);
    }
}elseif ($action == 'getUserById') {
    if (authenticate()) {
        if (isset($data->id)) {
            $userController->getUserById($data->id);  // Llama al método para obtener un usuario por ID
        } else {
            echo json_encode(["message" => "ID del usuario no proporcionado"]);
        }
    } else {
        echo json_encode(["message" => "Autenticación fallida"]);
    }
}elseif ($action == 'getUserByToken') {
    if (authenticate()) {
        $userController->getUserByToken();  // Llama al método para obtener el usuario por token
    } else {
        echo json_encode(["message" => "Autenticación fallida"]);
    }
}
elseif ($action == 'update') {
    if (authenticate()) {
        if (isset($data->id) && isset($data->nombre) && isset($data->correo)) {
            $userController->updateUser($data);
        } else {
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }
} elseif ($action == 'delete') {
    if (authenticate()) {
        $userController->deleteUser($data);
    }
} else {
    echo json_encode(["message" => "Acción no válida"]);
}
?>
