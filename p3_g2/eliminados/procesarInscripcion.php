<?php
    session_start();

    // Mostrar errores de PHP en la página web
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $nombre_curso = isset($_POST['nombre_curso']) ? $_POST['nombre_curso'] : '';
    $formEnviando = isset($_POST["inscripcion"]);
    if (! $formEnviando) {
        header("Location: inscripcion.php?nombre_curso=$nombre_curso");
        exit();
    }

    require_once __DIR__.'/includes/utils.php';

    $erroresFormulario = [];

    $nombre_usuario = filter_input(INPUT_POST,'nombre_usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ( ! $email || empty($nombre=trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresFormulario['email'] = 'Introduce una dirección de correo electrónico válida.';
    }

    if (count($erroresFormulario) == 0) {
        $conn = conexionBD();

        $query=sprintf("SELECT id FROM Estudiante WHERE (nombre_usuario = '%s' AND apellido = '%s' AND email = '%s'"
            , $nombre_usuario
            , $apellido
            , $email
        );
        $rs = $conn->query($query);
        if ($rs) {
            if ($rs->num_rows == 0) {
                $erroresFormulario[] = "El estudiante no existe";
                $rs->free();
            } else {
                $row = $rs->fetch_assoc();
                $u_id = $row['id'];
                $rs->free();
                $query=sprintf("INSERT INTO Registrado(u_id,curso_id) VALUES('%s','%s')"
                    , $u_id
                    , $nombre_curso
                );
                if ($conn->query($query)) {
                    //El estudiante es bien registrado al curso
                    header('cursos.php');
                    exit();
                } else {
                    echo "Error SQL ({$conn->errno}):  {$conn->error}";
                    exit();
                }
            }
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Inscripcion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/login_registro.css">
        <link rel="stylesheet" href="CSS/topBar.css">
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
                            <?=  generarError('email', $erroresFormulario) ?>
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