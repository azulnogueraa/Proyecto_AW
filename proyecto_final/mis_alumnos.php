<?php
require_once __DIR__ . '/includes/src/config.php';

$tituloPagina = 'Mis Alumnos';
$contenidoPrincipal = '<h1>Mis Alumnos</h1>';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

// Obtener datos del profesor actual
$profesorActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId("Profesor", $_SESSION['id']);
if (!$profesorActual) {
    header('Location: login.php');
    exit();
}

// Obtener cursos asignados al profesor actual
$cursosAsignados = es\ucm\fdi\aw\Curso::obtenerCursosPorProfesor($profesorActual->getId());

if (empty($cursosAsignados)) {
    $contenidoPrincipal .= '<p>No tienes cursos asignados actualmente.</p>';
} else {
    // Construir la lista de alumnos asignados por cada curso
    foreach ($cursosAsignados as $curso) {
        $contenidoPrincipal .= "<h2>Alumnos de {$curso->getNombre()}</h2>";
        $IdalumnosAsignados = es\ucm\fdi\aw\Registrado::getEstudianteIdRegistrados($curso->getNombre());
        $contenidoPrincipal .= '<ul>';
        if (!empty($IdalumnosAsignados)) {
            foreach ($IdalumnosAsignados as $alumnoId) {
                $nombreAlumno = htmlspecialchars(es\ucm\fdi\aw\Estudiante::obtenerIdporNombre($alumnoId), ENT_QUOTES, 'UTF-8');
                $contenidoPrincipal .= "<li>{$nombreAlumno}</li>";
            }
        } else {
            $contenidoPrincipal .= '<li>No tienes alumnos asignados actualmente.</li>';
        }
        $contenidoPrincipal .= '</ul>';
    }
}

require_once __DIR__ . '/includes/vistas/plantillas/plantilla.php';