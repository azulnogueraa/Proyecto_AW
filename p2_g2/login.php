<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pagina Principal</title>
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/login_registro.css">
    </head>
    <body>
    
        <!-- topbar -->
        <?php require "includes/vistas/comun/topbar.php"; ?>

     <main>
            <article>
                <h1>Log In</h1>
                <form method="post" action="procesarLogin.php" name="signin-form">
                <fieldset>
                    <div class="legenda">
                        <legend>Datos para reingresar</legend>
                    </div>
                    <div>
                        <label for="nombreUsuario">Nombre de usuario:</label>
                        <input id="nombreUsuario" type="text" name="nombreUsuario" />
                    </div>
                    <div>
                        <label for="password">Contraseña:</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div class="boton">
                        <button type="submit" name="registro">Log In</button>
                    </div>
                </fieldset>
                </form>
            </article>
        </main> 
        
    </body>

</html>