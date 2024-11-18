<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function authenticate() {
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $jwt = str_replace("Bearer ", "", $authHeader);

        try {
            $key = "tu_clave_secreta"; // Clave secreta que usaste al generar el token
            $decoded = JWT::decode($jwt, new Key($key, 'HS256')); // Usar la nueva firma del método
            return true; // Token válido
        } catch (Exception $e) {
            echo json_encode(["message" => "Acceso denegado. Token inválido", "error" => $e->getMessage()]);
            return false; // Token inválido
        }
    }

    echo json_encode(["message" => "No autorizado"]);
    return false; // No se proporcionó token
}
?>
