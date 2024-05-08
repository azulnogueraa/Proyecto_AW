<?php
require_once __DIR__ . '/includes/src/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tituloPagina = 'Mis Cursos';
$contenidoPrincipal = '<h1>Mis Cursos</h1>';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

// Obtener datos del profesor actual
$profesorActual = es\ucm\fdi\aw\Profesor::buscaProfesorPorId($_SESSION['id']);

if (!$profesorActual) {
    header('Location: login.php');
    exit();
}
// Obtener cursos asignados al profesor actual
$cursosAsignados = $profesorActual->cursosDelProfesor($profesorActual-> getNombreUsuarioProfesor());

// Construir la lista de cursos asignados
$contenidoPrincipal .= '<h2>Cursos Asignados</h2>';
$contenidoPrincipal .= '<ul>';

// Listar cursos asignados
if (!empty($cursosAsignados)) {
    foreach ($cursosAsignados as $curso) {
        $nbcurso = htmlspecialchars($curso->getNombre(), ENT_QUOTES, 'UTF-8');
        $contenidoPrincipal .= "<li><a href='chat.php?nombre_curso={$nbcurso}'>{$nbcurso}</a></li>";
    }
} else {
    $contenidoPrincipal .= '<li>No tienes cursos asignados actualmente.</li>';
}

$contenidoPrincipal .= '</ul>';

require_once __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
