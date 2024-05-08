<?php
require_once __DIR__.'/includes/src/config.php';

// Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['tipo_usuario']);
unset($_SESSION['nombre']);
session_destroy();

// Configura el título de la página y el contenido principal
$tituloPagina = 'Logout';
$contenidoPrincipal = <<<EOS
<h1>¡Hasta pronto!</h1>
<p>Has cerrado sesión correctamente.</p>
<a href="login.php">Iniciar Sesión</a>
EOS;

// Incluye la plantilla para mostrar la página completa
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
