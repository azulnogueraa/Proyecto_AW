<?php
// Este fichero recibe un mensaje en json y lo anade a la base de datos
require_once '../includes/src/config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_SESSION['login']) && $_SESSION["login"] == true) {
        // Recuperamos el mensaje en formato JSON y convertimos a un array de PHP
        $dataJson = file_get_contents('php://input');
        $data = json_decode($dataJson);

        // Recuperamos el usuario conectado y su rol
        $usuario = es\ucm\fdi\aw\Usuario::buscaUsuario($_SESSION['nombre']);
        $tipo = $_SESSION['tipo_usuario'];
        $roles = [
            es\ucm\fdi\aw\Usuario::ADMIN_ROLE => "Administrador",
            es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE => "Estudiante",
            es\ucm\fdi\aw\Usuario::PROFESOR_ROLE => "Profesor"
        ];
        $rol = $roles[$tipo] ?? "";

        if(isset($data->mensaje) && !empty($data->mensaje) && isset($data->nombre_curso)) {
            $conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("INSERT INTO Mensaje(mensaje, user_id, tipo_usuario, nombre_curso) VALUES ('%s', '%s', '%s', '%s')"
                , $conn->real_escape_string($data->mensaje)
                , $conn->real_escape_string($usuario->getId())
                , $conn->real_escape_string($rol)
                , $conn->real_escape_string($data->nombre_curso)
            );
            if($conn->query($query)) {
                http_response_code(201);
            } else {
                http_response_code(500);
                echo json_encode(['error' => "Error BD ({$conn->errno}): {$conn->error}"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos de solicitud inválidos']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Debes estar conectado para enviar un mensaje']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo HTTP no permitido']);
}