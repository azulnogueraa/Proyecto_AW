<?php
require_once __DIR__.'/includes/src/config.php';

//Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['tipo_usuario']);
unset($_SESSION['nombre']);

session_destroy();
$tituloPagina = 'logout';
$contenidoPrincipal = <<<EOS
<h1>Hasta pronto!</h1>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';