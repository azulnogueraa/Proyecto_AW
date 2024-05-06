<?php
require_once '../includes/src/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_SESSION['login']) && $_SESSION["login"] == true) {
        if(isset($_GET['nombre_curso']) && isset($_GET['lastId'])){
            // Recuperamos el id del curso y el ultimo id de mensaje asociado
            $nombre_curso = filter_var($_GET['nombre_curso'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lastId = filter_var($_GET['lastId'], FILTER_SANITIZE_NUMBER_INT);

            // Initializamos un filtro para recuperar los mensajes
            $filtro = ($lastId > 0) ? " AND Mensaje.id > $lastId" : '';

            // Recuperamos el tipo de usuario conectado para acceder a la tabla correspondiente

            $conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("SELECT Mensaje.id, Mensaje.mensaje, Mensaje.created_at, Mensaje.tipo_usuario,
                            COALESCE(
                                Estudiante.nombre_usuario,
                                Profesor.nombre_usuario,
                                Administrador.nombre_usuario
                            ) AS nombre_usuario
                            FROM Mensaje
                            LEFT JOIN Estudiante ON Mensaje.user_id = Estudiante.id AND Mensaje.tipo_usuario = 'Estudiante'
                            LEFT JOIN Profesor ON Mensaje.user_id = Profesor.id AND Mensaje.tipo_usuario = 'Profesor'
                            LEFT JOIN Administrador ON Mensaje.user_id = Administrador.id AND Mensaje.tipo_usuario = 'Administrador'
                            WHERE Mensaje.nombre_curso = '%s'
                            $filtro
                            ORDER BY Mensaje.id ASC
                            LIMIT 5"
                            , $conn->real_escape_string($nombre_curso));
            // $query = sprintf("SELECT Mensaje.id, Mensaje.mensaje, Mensaje.created_at, $tabla.nombre_usuario FROM Mensaje LEFT JOIN $tabla ON Mensaje.user_id = $tabla.id WHERE Mensaje.nombre_curso = '%s' $filtro ORDER BY Mensaje.id ASC LIMIT 5"
            //     , $conn->real_escape_string($nombre_curso)
            // );
            $rs = $conn->query($query);
            if ($rs) {
                // Creamos un array con los mensajes
                $mensajes = [];
                while ($row = $rs->fetch_assoc()) {
                    $mensajes[] = [
                        'id' => $row['id'],
                        'mensaje' => $row['mensaje'],
                        'created_at' => $row['created_at'],
                        'nombre_usuario' => $row['nombre_usuario'],
                        'tipo_usuario' => $row['tipo_usuario']
                    ];
                }
                // Enviamos los mensajes en formato JSON
                echo json_encode($mensajes);
                $rs->free();
            } else {
                http_response_code(500);
                echo json_encode(['error' => "Error BD ({$conn->errno}): {$conn->error}"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Parametros de solicitud no validos']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Debes estar conectado para recibir los mensajes']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo HTTP no permitido']);
}