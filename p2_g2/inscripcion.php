<!-- boceto 8 -->
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
    </head>

    <body>
        <?php require "includes/vistas/comun/topbar.php"; ?>
        <div class="formularios">
            <form method="post" action="procesarInscipcion.php" name="signin-form">
                <div class="form-element">
                    <label>Nombre</label>
                    <input type="text" name="username" pattern="[a-zA-Z0-9]+" required value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" />
                    <label>Apellido</label>
                    <input type="text" name="userApellido" pattern="[a-zA-Z0-9]+" required value="<?= isset($_SESSION['userApellido']) ? $_SESSION['userApellido'] : ''; ?>" />
                    <label>Correo</label>
                    <input type="text" name="correo" required value="<?php echo isset($_SESSION['correo']) ? $_SESSION['correo'] : ''; ?>" />
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">Acepto los Términos y Condiciones</label>
                    <button type="submit">Siguiente</button>
                </div>
            </form>
        </div>
    </body>
</html>

