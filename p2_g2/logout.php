<?php
//Inicio del procesamiento
session_start();

//Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['esAdmin']);
unset($_SESSION['nombre']);
unset($_SESSION['esProfesor']);

session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Logout</title>
	<link rel="stylesheet" type="text/css" href="CSS/topBar.css" />
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
        <div id="contenedor">
            <main>
                <article>
                    <h1>Hasta pronto!</h1>
                </article>
            </main>
        </div>
    </body>
</html>
