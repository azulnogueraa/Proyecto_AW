<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Registro</title>
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/login_registro.css">
    </head>
    <body>
        <!-- topbar -->
        <?php require "includes/vistas/comun/topbar.php"; ?>
        
        <main>
            <article>
                <h1>Registro de usuario</h1>
                <form action="procesarRegistro.php" method="POST">
                <fieldset>
                    <div class="legenda">
                        <legend>Datos para el registro</legend>
                    </div>
                    <div>
                        <label for="nombre_usuario">Nombre de usuario:</label>
                        <input id="nombre_usuario" type="text" name="nombre_usuario" />
                    </div>
                    <div>
                        <label for="apellido">Apellido:</label>
                        <input id="apellido" type="text" name="apellido" />
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input id="email" type="email" name="email" />
                    </div>
                    <div>
                        <label for="password">Password:</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div>
                        <label for="password2">Reintroduce el password:</label>
                        <input id="password2" type="password" name="password2" />
                    </div>
                    <div class="boton">
                        <button type="submit" name="registro">Registrar</button>
                    </div>
                </fieldset>
                </form>
            </article>
        </main>
    </body>
</html>