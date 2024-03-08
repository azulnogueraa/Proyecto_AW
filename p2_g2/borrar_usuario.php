<?php
    session_start();

    // Verifica si se envió el formulario y si se proporcionó un nombre de usuario para eliminar
    if (isset($_POST["borrar"]) && isset($_POST["usuario"])) {
        // Incluye el archivo de utilidades para la conexión a la base de datos
        require_once "includes/utils.php";
        // Establece la conexión a la base de datos
        $mysqli = conexionBD();
        
        // Sanitiza y escapa el nombre de usuario
        $usuario = $mysqli->real_escape_string($_POST["usuario"]);
        
        // Construye y ejecuta la consulta para borrar el usuario de las tablas correspondientes
        $sql = "DELETE FROM Estudiante WHERE nombre_usuario = '$usuario';
                DELETE FROM Profesor WHERE nombre_usuario = '$usuario';
                DELETE FROM Administrador WHERE nombre_usuario = '$usuario'";
        if ($mysqli->multi_query($sql)) {
            echo "El usuario '$usuario' ha sido eliminado correctamente.";
        } else {
            echo "Error al intentar borrar el usuario.";
        }

        // Cierra la conexión a la base de datos
        $mysqli->close();
    } else {
        // Si no se envió el formulario correctamente, redirige a alguna página adecuada
        header("Location: ajustes.php");
        exit();
    }
?>
