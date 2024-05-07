<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/includes/src/config.php';

// Verificar si la clase FormularioAgregarCurso existe
if (class_exists('es\ucm\fdi\aw\FormularioAgregarCurso')) {
    // Crear instancia del formulario de inscripción
    $form = new es\ucm\fdi\aw\FormularioAgregarCurso();
    $htmlFormInscripcion = $form->gestiona();
} else {
    // Manejar error si la clase no está disponible
    $htmlFormInscripcion = '<p>Error: no se puede encontrar el formulario de inscripción.</p>';
}

// Escapar contenido para prevenir XSS
$htmlFormInscripcion = htmlspecialchars($htmlFormInscripcion, ENT_QUOTES, 'UTF-8');

// Configurar el contenido principal
$tituloPagina = 'Inscripción';
$contenidoPrincipal = <<<EOS
    $htmlFormInscripcion
EOS;

// Incluir la plantilla para mostrar la página
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
