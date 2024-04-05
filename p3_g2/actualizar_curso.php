<?php
session_start();
require_once "includes/utils.php"; // Incluye el archivo de utilidades
$mysqli = conexionBD(); // Establece la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreCurso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $profesorId = $_POST['profesor_id'];
    $fechaCreacion = $_POST['fecha_creacion'];
    $duracion = $_POST['duracion'];
    $nivelDificultad = $_POST['nivel_dificultad'];
    $categoria = $_POST['categoria'];
    $requisitosPrevios = $_POST['requisitos_previos'];
    $precio = $_POST['precio'];
    $estadoCurso = $_POST['estado_curso'];

    // Preparar la consulta SQL para actualizar el curso
    $sql = "UPDATE Curso SET descripcion=?, profesor_id=?, fecha_creacion=?, duracion=?, nivel_dificultad=?, categoria=?, requisitos_previos=?, precio=?, estado_curso=? WHERE nombre_curso=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sisssssdss", $descripcion, $profesorId, $fechaCreacion, $duracion, $nivelDificultad, $categoria, $requisitosPrevios, $precio, $estadoCurso, $nombreCurso);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Curso actualizado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el curso: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    // Redirigir de vuelta a la página de ajustes
    header("Location: ajustes.php");
    exit();
} else {
    // Si la solicitud no es POST, redirigir a la página de ajustes
    header("Location: ajustes.php");
    exit();
}
?>
