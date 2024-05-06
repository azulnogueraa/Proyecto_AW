<?php
require_once __DIR__.'/includes/srcconfig.php';

$tituloPagina = 'Cursos';
//función para generar la visualización de cursos
function toBox($nombre, $precio, $descripcion) {
    $contenido = "<div class='box-cursos'>";
    $contenido .= "<h2 class='nombre-cursos'>$nombre</h2>";
    $contenido .= "<div class='precio-cursos'>Precio: $precio EUR</div>";
    $contenido .= "<p class='descripcion-cursos'>$descripcion</p>";
    $contenido .= "<a href='curso.php?nombre_curso=$nombre' class='button-cursos'>Ver curso</a>";
    $contenido .= "</div>";
    return $contenido;
}

$contenidoPrincipal = "<div id='contenedor-cursos'>";

$cursos = es\ucm\fdi\aw\Curso::obtenerCursos();
if ($cursos) {
    foreach($cursos as $curso) {
        $contenidoPrincipal .= toBox($curso->getNombre(), $curso->getPrecio(), $curso->getDescripcion());
    }
} else {
    $contenidoPrincipal .= '<p>No hay cursos disponibles en este momento.</p>';
}

// Finaliza el contenedor de cursos
$contenidoPrincipal .= '</div>';
// Incluye la plantilla HTML que muestra el contenido principal
include 'includes/vistas/plantillas/plantilla.php';
