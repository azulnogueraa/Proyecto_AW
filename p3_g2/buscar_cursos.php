<?php
    //session_start();
?>
<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title> Buscar Cursos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div id="contenedor_buscar_cursos">
    <div class="content"> 
        <h1>Buscar Curso</h1>
        <input type="text" id="searchInput" placeholder="Buscar curso...">
        <div id="searchResults"></div>
    </div>
</div> -->

<?php 
    //require_once "includes/vistas/comun/box_curso.php";
    //require "includes/vistas/comun/topbar.php";
?>

<!-- Enlace al archivo JS -->
<!--<script src="buscar_cursos.js"></script>
</body>
</html> -->

<?php
require_once __DIR__.'/includes/config.php';
//require_once "includes/vistas/comun/curso.php";// no estoy seguro
$tituloPagina = 'Buscar cursos';

$contenidoPrincipal = <<<EOS
<div id="contenedor_buscar_cursos">
    <div class="content"> 
        <h1>Buscar Curso</h1>
        <input type="text" id="searchInput" placeholder="Buscar curso...">
        <div id="searchResults"></div>
    </div>
</div>
<script src="JS/buscar_cursos.js"></script>
EOS;
include 'includes/vistas/plantillas/plantilla.php';
