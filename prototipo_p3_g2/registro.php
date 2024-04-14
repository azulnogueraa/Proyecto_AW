<?php
require_once __DIR__.'/includes/config.php';

$form = new es\ucm\fdi\aw\FormularioRegistro();
$htmlFormLogin = $form->gestiona();
$tituloPagina = 'Registro';
$contenidoPrincipal = <<<EOS
$htmlFormLogin
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';