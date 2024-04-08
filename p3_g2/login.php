<?php

require_once __DIR__.'/includes/config.php';

$form = new es\ucm\fdi\aw\FormularioLogin();
$htmlFormLogin = $form->gestiona();
$tituloPagina = 'Login';
$contenidoPrincipal = <<<EOS
<h1>Log In</h1>
$htmlFormLogin
EOS;
require __DIR__.'/includes/vistas/plantillas/plantillaFormulario.php';