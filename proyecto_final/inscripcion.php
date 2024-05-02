<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/includes/config.php';

// Obtener el nombre del curso de la URL
$nombre_curso = $_GET['nombre_curso'] ?? '';

// Crear instancia del formulario de inscripción y pasar el nombre del curso
$form = new es\ucm\fdi\aw\FormularioInscripcion($nombre_curso);
$htmlFormInscripcion = $form->gestiona();

// Configurar el contenido principal
$tituloPagina = 'Inscripción';
$contenidoPrincipal = <<<EOS
    $htmlFormInscripcion
EOS;

// Incluir la plantilla para mostrar la página
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
