<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Registro</title>
        <link rel="stylesheet" href="CSS/topBar.css">
        <link rel="stylesheet" href="CSS/registro_login.css">
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
        
        <div class="formularios">

        <h1>Registro</h1>

        <form method="post" action="registro.php" name="signup-form">
            <div class="form-element">
            <label>Username</label>
            <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div class="form-element">
            <label>Email</label>
            <input type="email" name="email" required />
            </div>
            <div class="form-element">
            <label>Password</label>
            <input type="password" name="password" required />
            </div>
            <button type="submit" name="register" value="register">Register</button>
        </form>

        </div>
    </body>

</html>