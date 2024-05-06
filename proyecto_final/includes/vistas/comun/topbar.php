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
        
        <?php 
        // Si esta logueado y es estudiante que muestre la pestaña de perfil de usuario 
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE && isset($_SESSION["login"]) && $_SESSION["login"] === true) {
            echo "<a href='cursos.php' class='topbar-item'>Cursos</a>";
            echo "<a href='perfil.php' class='topbar-item'>Perfil</a>";
            echo "<a href='buscar_cursos.php' class='topbar-item'>Buscar Curso</a>";
        }
        ?>
        <?php
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === es\ucm\fdi\aw\Usuario::PROFESOR_ROLE && isset($_SESSION["login"]) && $_SESSION["login"] === true) {
                echo "<a href='mis_cursos.php' class='topbar-item'>Mis Cursos</a>";
                echo "<a href='mis_alumnos.php' class='topbar-item'>Mis Alumnos</a>";
            }
        ?>
        <?php
        // Verificar si el usuario es un administrador y está autenticado
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === es\ucm\fdi\aw\Usuario::ADMIN_ROLE && isset($_SESSION["login"]) && $_SESSION["login"] === true) {
            echo "<a href='cursos.php' class='topbar-item'>Cursos</a>";
            echo "<a href='ajustes.php' class='topbar-item'>Ajustes</a>";
            echo "<a href='buscar_cursos.php' class='topbar-item'>Buscar Curso</a>";
        }
        ?>
    
    </div>
    <?php
    // Verificar si la función mostrarSaludo() no está definida
    if (!function_exists('mostrarSaludo')) {
        // Definir la función mostrarSaludo()
        function mostrarSaludo() {
            $saludos = [
                es\ucm\fdi\aw\Usuario::ADMIN_ROLE => "Administrador",
                es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE => "Estudiante",
                es\ucm\fdi\aw\Usuario::PROFESOR_ROLE => "Profesor"
            ];
            if (isset($_SESSION['login']) && ($_SESSION['login'] === true)) {
                $tipo_usuario = $_SESSION['tipo_usuario'];
                $nombre = $_SESSION['nombre'];
                $saludo = $saludos[$tipo_usuario] ?? "Usuario desconocido";
                return "Bienvenido, $saludo $nombre <a href='logout.php' class='salir-topbar'>(salir)</a>";   
            } else {
                return "Usuario desconocido.";
            }
        }
    }
    ?>
    <!-- Mostrar el saludo -->
    <div class="saludo-topbar"><?= mostrarSaludo(); ?></div>
</header>
