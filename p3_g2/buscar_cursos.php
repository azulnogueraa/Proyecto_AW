<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cursos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/cursos.css">
    <link rel="stylesheet" href="CSS/topBar.css">
</head>
<body>
<div class="container-cursos">
    <div class="content"> <!-- add -cursos? but there is no content in cursos.css -->
        <h1>Buscar Curso</h1>
        <input type="text" id="searchInput" placeholder="Buscar curso...">
        <div id="searchResults"></div>
    </div>
</div>

<!-- topbar -->
<?php 
    require_once "includes/vistas/comun/box_curso.php";
    require "includes/vistas/comun/topbar.php";
?>

<!-- Enlace al archivo JS -->
<script src="buscar_cursos.js"></script>
</body>
</html>
