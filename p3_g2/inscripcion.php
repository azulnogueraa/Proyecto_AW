<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Inscripcion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/login_registro.css">
    </head>
    <body>
        <?php require "includes/vistas/comun/topbar.php"; ?>
        <main>
            <article>
                <h1>Formulario de inscripcion</h1>
                <form action="procesarInscripcion.php" method="POST">
                    <!-- Campo oculto para almacenar el nombre del curso -->    
                    <input type="hidden" name="nombre_curso" value="<?= isset($_GET['nombre_curso']) ? $_GET['nombre_curso'] : ''; ?>">
                    
                    <fieldset>
                        <div class="legenda">
                            <legend>Datos para la inscripcion</legend>
                        </div>
                        <div>
                            <label for="nombre_usuario">Nombre de usuario:</label>
                            <input id="nombre_usuario" type="text" name="nombre_usuario" required value="<?= isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : ''; ?>"/>
                        </div>
                        <div>
                            <label for="apellido">Apellido:</label>
                            <input id="apellido" type="text" name="apellido" required value="<?= isset($_SESSION['apellido']) ? $_SESSION['apellido'] : ''; ?>"/>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input id="email" type="email" name="email" required 
                            value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
                        </div>
                        <div>
                            <input type="checkbox" id="terminos" name="terminos" required>
                            <label for="terminos">Acepto los Términos y Condiciones</label>
                        </div>
                        <div class="boton">
                            <button type="submit">Siguiente</button>
                        </div>
                    </fieldset>
                </form>
            </article>
        </main>
    </body>
</html>