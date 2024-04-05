<?php
session_start();
require_once "includes/utils.php"; // Incluye el archivo de utilidades
$mysqli = conexionBD(); // Establece la conexión a la base de datos

// Verifica si hay un mensaje almacenado en la sesión
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;

// Elimina el mensaje de la sesión para que no se muestre más de una vez
unset($_SESSION['mensaje']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ajustes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <!-- topbar -->
    <?php 
    require_once "includes/vistas/comun/box_curso.php";
    require "includes/vistas/comun/topbar.php";

    // Verifica si el usuario es un administrador
    if (isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"] === true) {
        // Consulta para obtener todos los usuarios
        $sqlUsuarios = "SELECT nombre_usuario FROM Estudiante 
                        UNION 
                        SELECT nombre_usuario FROM Profesor 
                        UNION 
                        SELECT nombre_usuario FROM Administrador";
        $resultUsuarios = $mysqli->query($sqlUsuarios);

        // Consulta para obtener todos los cursos
        $sqlCursos = "SELECT nombre_curso FROM Curso";
        $resultCursos = $mysqli->query($sqlCursos);

        if ($resultUsuarios->num_rows > 0) {
            echo "<div class='container'>";
            echo "<h2>Borrar usuario</h2>";
            // Muestra el mensaje de confirmación si existe
            if ($mensaje) {
                echo "<p>$mensaje</p>";
            }
            echo "<form action='borrar_usuario.php' method='POST'>";
            echo "<label for='usuario'>Selecciona el usuario:</label>";
            echo "<select name='usuario' id='usuario'>";
            // Imprime las opciones para seleccionar usuarios
            while ($row = $resultUsuarios->fetch_assoc()) {
                echo "<option value='" . $row["nombre_usuario"] . "'>" . $row["nombre_usuario"] . "</option>";
            }
            echo "</select>";
            echo "<button type='submit' name='borrar'>Borrar usuario</button>";
            echo "</form>";
            echo "</div>";

            echo "<h2>Administrar Cursos</h2>";
            echo "<form action='editar_curso.php' method='GET'>";
            echo "<label for='curso'>Selecciona el curso:</label>";
            echo "<select name='nombre_curso' id='curso'>";
            // Imprime las opciones para seleccionar cursos
            while ($row = $resultCursos->fetch_assoc()) {
                echo "<option value='" . $row["nombre_curso"] . "'>" . $row["nombre_curso"] . "</option>";
            }
            echo "</select>";
            echo "<button type='submit'>Editar Curso</button>";
            echo "</form>";

        } else {
            echo "No hay usuarios para mostrar.";
        }
    } else {
        echo "Acceso no autorizado. Debes ser administrador para acceder a esta página.";
    }
    ?>
</body>
</html>
