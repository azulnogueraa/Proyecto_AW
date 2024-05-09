<?php
require_once "includes/src/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y aplicar sanitización
    $nombreCurso = htmlspecialchars($_POST['nombre_curso'] ?? '', ENT_QUOTES, 'UTF-8');
    $descripcion = htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');
    $duracion = htmlspecialchars($_POST['duracion'] ?? '', ENT_QUOTES, 'UTF-8');
    $nivelDificultad = htmlspecialchars($_POST['nivel_dificultad'] ?? '', ENT_QUOTES, 'UTF-8');
    $categoria = htmlspecialchars($_POST['categoria'] ?? '', ENT_QUOTES, 'UTF-8');
    $precio = floatval($_POST['precio'] ?? 0); // Asegurarse de que sea un número

    // Intentar cargar el curso a editar
    $datosCurso = es\ucm\fdi\aw\Curso::editarCurso($nombreCurso);

    if ($datosCurso) {
        // Crear una nueva instancia de Curso con los datos y nuevos valores
        $curso = new es\ucm\fdi\aw\Curso(
            $datosCurso['nombre_curso'],
            $datosCurso['profesor_id'],
            $datosCurso['descripcion'],
            $datosCurso['duracion'],
            $datosCurso['categoria'],
            $datosCurso['nivel_dificultad'],
            $datosCurso['precio']
        );

        // Actualizar los atributos del curso con los nuevos datos del formulario
        $curso->setDescripcion($descripcion);
        $curso->setDuracion($duracion);
        $curso->setNivelDificultad($nivelDificultad);
        $curso->setCategoria($categoria);
        $curso->setPrecio($precio);

        // Guardar los cambios en la base de datos utilizando el método actualizarCurso()
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
