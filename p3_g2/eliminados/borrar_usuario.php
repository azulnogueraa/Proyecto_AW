<?php
session_start();
require_once "includes/utils.php"; // Incluye el archivo de utilidades para la conexión a la base de datos
$mysqli = conexionBD(); // Establece la conexión a la base de datos

// Verifica si se envió el formulario y si se proporcionó un nombre de usuario para eliminar
if (isset($_POST["borrar"]) && isset($_POST["usuario"])) {
    // Sanitiza y escapa el nombre de usuario
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);

    // Construye y ejecuta la consulta para borrar el usuario de las tablas correspondientes
    $sql = "DELETE FROM Estudiante WHERE nombre_usuario = '$usuario';
            DELETE FROM Profesor WHERE nombre_usuario = '$usuario';
            DELETE FROM Administrador WHERE nombre_usuario = '$usuario'";
    if ($mysqli->multi_query($sql)) {
        $_SESSION['mensaje'] = "El usuario '$usuario' ha sido eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al intentar borrar el usuario.";
    }

    // Redirige de vuelta a ajustes.php
    header("Location: ajustes.php");
    exit();
} else {
    // Si no se envió el formulario correctamente, redirige a ajustes.php
    header("Location: ajustes.php");
    exit();
}
?>
