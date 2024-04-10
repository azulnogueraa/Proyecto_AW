<?php
require_once __DIR__.'/includes/config.php';
//Inicio del procesamiento
//session_start();

$form = new es\ucm\fdi\aw\FormularioRegistro();
$htmlFormLogin = $form->gestiona();
$tituloPagina = 'Registro';
$contenidoPrincipal = <<<EOS
<h1>Registro de Usuario</h1>
$htmlFormLogin
EOS;
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>