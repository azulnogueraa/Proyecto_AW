<?php
require_once __DIR__.'/includes/src/config.php';

$tituloPagina = 'Buscar cursos';

// Contenido principal de la página
$contenidoPrincipal = <<<EOS
<div id="contenedor_buscar_cursos">
    <div class="content"> 
        <h1>Buscar Curso</h1>
        <input type="text" id="searchInput" placeholder="Buscar curso...">
        <input type="number" id="searchPrecio" placeholder="Precio máximo (en EUR)">
        <button id="buscPrecio" type="button">&#x1F50D;</button>
        <div id="searchResults"></div>
    </div>
</div>
<script src="JS/buscar_cursos.js"></script>
EOS;

include 'includes/vistas/plantillas/plantilla.php';
