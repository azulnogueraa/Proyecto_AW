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
    <link rel="stylesheet" href="CSS/topBar.css">
    
  </head>
  <body>

    <!-- topbar -->
    <div class="topbar">
        <!-- Learnique -->
        <div class="topbar-name">
            <a class="topbar-name">Learnique</a>
        </div>
        <!-- topbar items -->
        <div>
            <a href="index.php" class="topbar-item">Inicio</a>
            <a href="login.php" class="topbar-item">Log In</a>
            <a href="registro.php" class="topbar-item">Registro</a>
            <a href="cursos.php" class="topbar-item">Cursos</a>
        </div>
        <?php
        function mostrarSaludo() {
            if (isset($_SESSION['login']) && ($_SESSION['login']===true)) {
                return "Bienvenido, {$_SESSION['nombre']} <a href='logout.php' class='salir'>(salir)</a>";
                
            } else {
                return "Usuario desconocido.";
            }
        }
        ?>
        <div class="saludo"><?= mostrarSaludo(); ?></div>

    </div>


    <div class="texto"> 
      <h1> Cursos Online para transformar tu realidad en tiempo récord</h1>
      <p>Clases en línea y en vivo dictadas por referentes de la industria, 
        enfoque 100% práctico, mentorías personalizadas y acceso a una gran 
        comunidad de estudiantes.</p>
      <button type="submit" name="cursos" value="cursos">Ver todos los cursos</button>
    </div>

  </body>