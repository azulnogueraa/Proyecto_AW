<?php
    session_start();
    $username = htmlspecialchars(trim(strip_tags($_REQUEST["username"])));
    $password = htmlspecialchars(trim(strip_tags($_REQUEST["password"])));
        
    if ($username == "user" && $password == "userpass") {
        $_SESSION["login"] = true;
        $_SESSION["nombre"] = "Usuario";
    }
    else if ($username == "admin" && $password == "adminpass") {
        $_SESSION["login"] = true;
        $_SESSION["nombre"] = "Administrador";
        $_SESSION["esAdmin"] = true;
    }
    else if ($username == "profesor" && $password == "profesorpass") {
        $_SESSION["login"] = true;
        $_SESSION["nombre"] = "Profesor";
        $_SESSION["esProfesor"] = true;
    }
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
   <?php require "includes/vistas/comun/topbar.php"; ?>

    <div class="texto"> 
        <?php
            session_start();
            if (!isset($_SESSION["login"])) { //Usuario incorrecto
                echo "<h1>ERROR</h1>";
                echo "<p>El usuario o contraseña no son válidos.</p>";
            }
            else { //Usuario registrado
                echo "<h1>Bienvenido {$_SESSION['nombre']}</h1>";
            }
        ?>
    </div>

  </body>
</html>