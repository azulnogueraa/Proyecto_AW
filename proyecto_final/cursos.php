<?php
require_once __DIR__.'/includes/src/config.php';

$tituloPagina = 'Cursos';

// Función para generar la visualización de cursos
function toBox($nombre, $precio, $descripcion) {
    $nombreEscapado = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    $descripcionEscapada = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
    $precioEscapado = htmlspecialchars($precio, ENT_QUOTES, 'UTF-8');
    $urlCurso = 'curso.php?nombre_curso=' . urlencode($nombre);

    $contenido = "<div class='box-cursos'>";
    $contenido .= "<h2 class='nombre-cursos'>$nombreEscapado</h2>";
    $contenido .= "<div class='precio-cursos'>Precio: $precioEscapado EUR</div>";
    $contenido .= "<p class='descripcion-cursos'>$descripcionEscapada</p>";
    $contenido .= "<a href='$urlCurso' class='button-cursos'>Ver curso</a>";
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
