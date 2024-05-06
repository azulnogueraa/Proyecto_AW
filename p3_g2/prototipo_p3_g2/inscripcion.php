<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/includes/config.php';

// Crear instancia del formulario de inscripción
$form = new es\ucm\fdi\aw\FormularioInscripcion();
$htmlFormInscripcion = $form->gestiona();

// Configurar el contenido principal
$tituloPagina = 'Inscripción';
$contenidoPrincipal = <<<EOS
    $htmlFormInscripcion
EOS;

// Incluir la plantilla para mostrar la página
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
