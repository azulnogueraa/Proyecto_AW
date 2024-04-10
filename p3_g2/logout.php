<?php

include 'includes/config.php';
//Inicio del procesamiento
//session_start();

//Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['tipo_usuario']);
unset($_SESSION['nombre']);

session_destroy();
$tituloPagina = 'logout';
$contenidoPrincipal = <<<EOS
<div>
    <main>
        <article>
            <h1>Hasta pronto!</h1>
        </article>
    </main>
</div>
EOS;
include 'includes/vistas/plantillas/plantilla.php';
?>

