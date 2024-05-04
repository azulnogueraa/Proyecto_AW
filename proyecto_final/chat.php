<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/includes/config.php';

function toBox($creacion, $u_id, $content) {
    $sender = $_SESSION['nombre'];
    $contenido = "<div class='box-cursos'>";
    $contenido .= "<h2 class='nombre-cursos'>$content</h2>";
    $contenido .= "<div class='precio-cursos'> $sender </div>";
    $contenido .= "<p class='descripcion-cursos'>$creacion</p>";
    $contenido .= "</div>";
    return $contenido;
}

$tituloPagina = 'chat curso';
$contenidoPrincipal = '';

if (isset($_GET['nombre_curso'])) {
    $id_curso = $_GET['nombre_curso'];
    $contenidoPrincipal = "<div id='contenedor-cursos'>";

    $result = es\ucm\fdi\aw\Mensaje::obtenerMensajes($id_curso);
    if ($result) {
        foreach ($result as $mensaje){
            $contenidoPrincipal .= toBox($mensaje->getCreacion(), $mensaje->getU_id(), $mensaje->getContenido());
        }
    } else {
        $contenidoPrincipal .= '<p>Todavia no hay mensajes.</p>';
    }
    $contenidoPrincipal .= '</div>';
    $contenidoPrincipal .= '<input type="text" id="message" required>
    <button id="sendmsg" type="button">\u2705</button>';
    $escaped_id_curso = json_encode($id_curso);
    $contenidoPrincipal .= "<script> var Ncurso = $escaped_id_curso; </script>";
    $contenidoPrincipal .= '<script src="JS/chat.js"></script>';
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';