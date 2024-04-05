<?php
session_start();
require_once "includes/utils.php"; // Incluye el archivo de utilidades
$mysqli = conexionBD(); // Establece la conexión a la base de datos

// Verifica si hay un mensaje almacenado en la sesión
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;

// Elimina el mensaje de la sesión para que no se muestre más de una vez
unset($_SESSION['mensaje']);

// Verificar si se ha enviado un nombre de curso por GET
if (isset($_GET['nombre_curso'])) {
    $nombreCurso = $_GET['nombre_curso'];

    // Consulta para obtener la información del curso seleccionado por nombre
    $sql = "SELECT * FROM Curso WHERE nombre_curso = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $nombreCurso);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $curso = $result->fetch_assoc();
    } else {
        $_SESSION['mensaje'] = "Curso no encontrado.";
        header("Location: ajustes.php");
        exit();
    }
} else {
    $_SESSION['mensaje'] = "Nombre de curso no especificado.";
    header("Location: ajustes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar Curso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <!-- topbar -->
    <?php require_once "includes/vistas/comun/topbar.php"; ?>

    <div class="container">
        <h2>Editar Curso</h2>
        <?php
        if ($mensaje) {
            echo "<p>$mensaje</p>";
        }
        ?>
        <form action="actualizar_curso.php" method="POST">
            <input type="hidden" name="nombre_curso" value="<?php echo $curso['nombre_curso']; ?>">
            
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50"><?php echo $curso['descripcion']; ?></textarea><br><br>
            
            <label for="profesor_id">ID del Profesor:</label>
            <input type="text" id="profesor_id" name="profesor_id" value="<?php echo $curso['profesor_id']; ?>" required><br><br>
            
            <label for="fecha_creacion">Fecha de Creación:</label>
            <input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $curso['fecha_creacion']; ?>" required><br><br>
            
            <label for="duracion">Duración:</label>
            <input type="text" id="duracion" name="duracion" value="<?php echo $curso['duracion']; ?>" required><br><br>
            
            <label for="nivel_dificultad">Nivel de Dificultad:</label>
            <input type="text" id="nivel_dificultad" name="nivel_dificultad" value="<?php echo $curso['nivel_dificultad']; ?>" required><br><br>
            
            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" value="<?php echo $curso['categoria']; ?>" required><br><br>
            
            <label for="requisitos_previos">Requisitos Previos:</label><br>
            <textarea id="requisitos_previos" name="requisitos_previos" rows="4" cols="50"><?php echo $curso['requisitos_previos']; ?></textarea><br><br>
            
            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" value="<?php echo $curso['precio']; ?>" required><br><br>
            
            <label for="estado_curso">Estado del Curso:</label>
            <input type="text" id="estado_curso" name="estado_curso" value="<?php echo $curso['estado_curso']; ?>" required><br><br>
            
            <button type="submit" name="editar">Actualizar Curso</button>
        </form>
    </div>
</body>
</html>
