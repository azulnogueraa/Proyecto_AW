<?php
require_once "includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreCurso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $duracion = $_POST['duracion'];
    $nivelDificultad = $_POST['nivel_dificultad'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];

    // Intentar cargar el curso a editar
    $curso = es\ucm\fdi\aw\Curso::editarCurso($nombreCurso);

    if ($curso instanceof es\ucm\fdi\aw\Curso) {
        // Actualizar los atributos del curso con los nuevos datos
        $curso->setDescripcion($descripcion);
        $curso->setDuracion($duracion);
        $curso->setNivelDificultad($nivelDificultad);
        $curso->setCategoria($categoria);
        $curso->setPrecio($precio);

        // Guardar los cambios en la base de datos utilizando el método actualizar()
        $guardado = $curso->actualizarCurso();

        if ($guardado) {
            $_SESSION['mensaje'] = "Curso actualizado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el curso.";
        }
    } else {
        $_SESSION['mensaje'] = "Curso no encontrado.";
    }

    // Redirigir de vuelta a la página de ajustes
    header("Location: ajustes.php");
    exit();
} else {
    // Si la solicitud no es POST, redirigir a la página de ajustes
    header("Location: ajustes.php");
    exit();
}
?>
