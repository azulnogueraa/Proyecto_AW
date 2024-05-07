<?php
require_once __DIR__ . '/includes/src/config.php';

$form = new es\ucm\fdi\aw\FormularioRegistro();
$htmlFormRegistro = $form->gestiona();
$tituloPagina = 'Registro';
$contenidoPrincipal = <<<EOS
$htmlFormRegistro
EOS;

require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
