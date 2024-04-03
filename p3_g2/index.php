<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Pagina Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/index.css">
    
    
  </head>
  <body>
    <!-- topbar -->
    <?php require "includes/vistas/comun/topbar.php"; ?>


    <div class="texto"> 
      <h1> Cursos Online para transformar tu realidad en tiempo récord</h1>
      <p>Clases en línea y en vivo dictadas por referentes de la industria, 
        enfoque 100% práctico, mentorías personalizadas y acceso a una gran 
        comunidad de estudiantes.</p>
      <button onclick="location.href='cursos.php';" type="submit" name="cursos" value="cursos">Ver todos los cursos</button>

    </div>

  </body>
</html>