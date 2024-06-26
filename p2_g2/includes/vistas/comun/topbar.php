<!DOCTYPE html>
<html lang ="es">
<head>
    <meta charset="utf-8">
    <title>TopBar</title>
    <link rel="stylesheet" href="CSS/topBar.css">
</head>
<body>
    <header class="topbar">
        <!-- Learnique -->
        <div class="topbar-name">
            <a href="#" class="topbar-name">Learnique</a>
        </div>
        <!-- topbar items -->
        <div class="topbar-items">
            <a href="index.php" class="topbar-item">Inicio</a>
            <?php
            // Verificar si el usuario no está autenticado
            if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
                echo "<a href='login.php' class='topbar-item'>Log In</a>";
                echo "<a href='registro.php' class='topbar-item'>Registro</a>";
            }
            ?>
            <a href="cursos.php" class="topbar-item">Cursos</a>
            <?php
            // Verificar si el usuario es un administrador y está autenticado
            if (isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"] === true && isset($_SESSION["login"]) && $_SESSION["login"] === true) {
                echo "<a href='ajustes.php' class='topbar-item'>Ajustes</a>";
            }
            ?>
        </div>
        <?php
        // Función para mostrar el saludo dependiendo del estado de sesión del usuario
        function mostrarSaludo() {
            if (isset($_SESSION['login']) && ($_SESSION['login'] === true)) {
                return "Bienvenido, {$_SESSION['tipo_usuario']} {$_SESSION['nombre']} <a href='logout.php' class='salir'>(salir)</a>";   
            } else {
                return "Usuario desconocido.";
            }
        }
        ?>
        <!-- Mostrar el saludo -->
        <div class="saludo"><?= mostrarSaludo(); ?></div>
    </header>
</body>
</html>
