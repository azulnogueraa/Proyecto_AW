<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pagina Principal</title>
        <link rel="stylesheet" href="CSS/registro_login.css">
    </head>
    <body>
    
        <!-- topbar -->
        <?php require "includes/vistas/comun/topbar.php"; ?>
        
        <div class="formularios">

            <h1>Log In</h1>

            <form method="post" action="procesarLogin.php" name="signin-form">
                <div class="form-element">
                    <label>Username</label>
                    <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
                </div>
                <div class="form-element">
                    <label>Password</label>
                    <input type="password" name="password" required />
                </div>
                <button type="submit" name="login" value="login">Log In</button>
            </form>

        </div>
    </body>

</html>