<?php
require_once __DIR__.'/includes/src/config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['login']) && $_SESSION['login'] && $_SESSION['tipo_usuario'] === es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE  && isset($_POST['nombre_curso'])) {
    $nombre_curso = htmlspecialchars($_POST['nombre_curso'], ENT_QUOTES, 'UTF-8');
    $curso = es\ucm\fdi\aw\Curso::buscaCursoPorNombre($nombre_curso);
    if($curso) {
        $tituloPagina = "Darse de baja " . $curso->getNombre();
        if(es\ucm\fdi\aw\Registrado::darseDeBaja($_SESSION['id'], $curso->getNombre())) {
            $contenidoPrincipal = "<h1>Te has dado de baja del curso correctamente.</h1>";
        } else {
            $contenidoPrincipal = "<h1>No se ha podido dar de baja del curso.</h1>";
        }
    } else {
        // Si no se encuentra el curso en la base de datos
        $tituloPagina = 'Error';
        $contenidoPrincipal = '<h1>Curso no encontrado.</h1>';
    }
} else {
    // Si no se proporciona el nombre del curso en los parámetros GET o no es conectado
    header('Location: index.php'); // Redirigir a la página principal
    exit();
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';