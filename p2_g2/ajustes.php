<?php
    session_start();
    // Incluye el archivo de utilidades
    require_once "includes/utils.php";
    // Establece la conexión a la base de datos
    $mysqli = conexionBD();
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
            // Realiza la consulta para obtener todos los usuarios
            $sql = "SELECT nombre_usuario FROM Estudiante 
                    UNION 
                    SELECT nombre_usuario FROM Profesor 
                    UNION 
                    SELECT nombre_usuario FROM Administrador";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='container'>";
                echo "<h2>Borrar usuario</h2>";
                echo "<form action='borrar_usuario.php' method='POST'>";
                echo "<label for='usuario'>Selecciona el usuario:</label>";
                echo "<select name='usuario' id='usuario'>";
                // Imprime las opciones para seleccionar usuarios
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["nombre_usuario"] . "'>" . $row["nombre_usuario"] . "</option>";
                }
                echo "</select>";
                echo "<button type='submit' name='borrar'>Borrar usuario</button>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "No hay usuarios para mostrar.";
            }
        } else {
            echo "Acceso no autorizado. Debes ser administrador para acceder a esta página.";
        }
    ?>
</body>
</html>
