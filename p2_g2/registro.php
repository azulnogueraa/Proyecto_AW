<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Registro</title>
        <link rel="stylesheet" href="CSS/registro_login.css">
    </head>
    <body>
        <!-- topbar -->
        <?php require "includes/vistas/comun/topbar.php"; ?>
        
        <main>
            <article>
                <h1>Registro de usuario</h1>
                <form action="procesarRegistro.php" method="POST">
                <fieldset>
                    <legend>Datos para el registro</legend>
                    <div>
                        <label for="nombreUsuario">Nombre de usuario:</label>
                        <input id="nombreUsuario" type="text" name="nombreUsuario" />
                    </div>
                    <div>
                        <label for="nombre">Nombre:</label>
                        <input id="nombre" type="text" name="nombre" />
                    </div>
                    <div>
                        <label for="password">Password:</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div>
                        <label for="password2">Reintroduce el password:</label>
                        <input id="password2" type="password" name="password2" />
                    </div>
                    <div>
                        <button type="submit" name="registro">Registrar</button>
                    </div>
                </fieldset>
                </form>
            </article>
        </main>
    </body>
</html>