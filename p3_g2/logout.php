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
    <link rel="icon" href="img/logo.jpg" type="image/png">
	<link rel="stylesheet" type="text/css" href="CSS/topBar.css" />
</head>
    <body>
        <!-- topbar -->
        <?php require "includes/vistas/comun/topbar.php"; ?>

        <div id="contenedor"><!-- maybe change the id -->
            <main>
                <article>
                    <h1>Hasta pronto!</h1>
                </article>
            </main>
        </div>
    </body>
</html>
