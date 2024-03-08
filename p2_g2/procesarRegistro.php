<?php
//Inicio del procesamiento
session_start();

// Mostrar errores de PHP en la página web
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$formEnviando = isset($_POST["registro"]);
if (! $formEnviando) {
    header('Location: registro.php');
    exit();
}

require_once __DIR__.'/includes/utils.php';

$erroresFormulario = [];

$nombre_usuario = filter_input(INPUT_POST,'nombre_usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $nombre_usuario || empty($nombre_usuario=trim($nombre_usuario)) || mb_strlen($nombre_usuario) < 3) {
	$erroresFormulario['nombre_usuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
}

$apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $apellido || empty($nombre=trim($apellido)) || mb_strlen($apellido) < 3) {
	$erroresFormulario['apellido'] = 'El apellido tiene que tener una longitud de al menos 3 caracteres.';
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $email || empty($nombre=trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$erroresFormulario['email'] = 'Introduce una dirección de correo electrónico válida.';
}
$domain = substr($email, strpos($email, '@') + 1);

// Verifica si el dominio del correo electrónico indica que es un administrador
if (strpos($domain, 'admin') !== false) {
    $table = 'Administrador';
    $role = ADMIN_ROLE;
} elseif (strpos($domain, 'estudiante') !== false) {
    $table = 'Estudiante';
    $role = ESTUDIANTE_ROLE;
} elseif (strpos($domain, 'profesor') !== false) {
    $table = 'Profesor';
    $role = PROFESOR_ROLE;
} else {
    $erroresFormulario['email'] = 'Introduce una dirección de correo electrónico válida.';
}

$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $password || empty($password=trim($password)) || mb_strlen($password) < 5 ) {
	$erroresFormulario['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
}

$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $password2 || empty($password2=trim($password2)) || $password != $password2 ) {
	$erroresFormulario['password2'] = 'Los passwords deben coincidir';
}

if (count($erroresFormulario) == 0) {
    $conn = conexionBD();

    $query=sprintf("SELECT * FROM %s WHERE (nombre_usuario = '%s' AND apellido = '%s') OR email = '%s'"
        , $table
        , $conn->real_escape_string($nombre_usuario)
        , $conn->real_escape_string($apellido)
        , $conn->real_escape_string($email)
    );
    $rs = $conn->query($query);
    if ($rs) {
        if ($rs->num_rows > 0) {
            $erroresFormulario[] = 'No puedes usar este usuario o correo electrónico porque ya están registrados. Por favor, intenta con otro.';
            $rs->free();
        } else {
            $query=sprintf("INSERT INTO %s(nombre_usuario,apellido,email,contrasena) VALUES('%s','%s','%s','%s')"
                , $table
                , $conn->real_escape_string($nombre_usuario)
                , $conn->real_escape_string($apellido)
                , $conn->real_escape_string($email)
                ,password_hash($password, PASSWORD_DEFAULT)
            );
            if ($conn->query($query)) {
                if ($conn->query($query)) {
                    $_SESSION["login"] = true;
                    $_SESSION["nombre"] = $nombre_usuario;
                    $_SESSION["email"] = $email;
                    $_SESSION["esAdmin"] = ($role == ADMIN_ROLE);
                    $_SESSION["esProfesor"] = ($role == PROFESOR_ROLE);
                    header('Location: index.php');
                    exit();
                } else {
                    echo "Error SQL ({$conn->errno}):  {$conn->error}";
                    exit();
                }
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
<html>
<head>
    <meta charset="utf-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/login_registro.css">
    <meta http-equiv="refresh" content="3;url=login.php">
</head>
<body>
    <?php require "includes/vistas/comun/topbar.php"; ?>
    <main>
        <article>
            <h1>Registro de usuario</h1>
            <?php
            if (isset($_SESSION['registro_exitoso'])) {
                echo '<div class="mensaje-exito">¡Tu cuenta se ha creado correctamente! Serás redirigido al inicio de sesión en unos segundos.</div>';
                unset($_SESSION['registro_exitoso']);
            }
            ?>
            <form action="procesarRegistro.php" method="POST">
            <fieldset>
                <div class="legenda">
                    <legend>Datos para el registro</legend>
                </div>
                <div>
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input id="nombreUsuario" type="text" name="nombreUsuario" />
                    <?=  generarError('nombreUsuario', $erroresFormulario) ?>
                </div>
                <div>
                    <label for="apellido">Apellido:</label>
                    <input id="apellido" type="text" name="apellido" />
                    <?=  generarError('apellido', $erroresFormulario) ?>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" />
                    <?=  generarError('email', $erroresFormulario) ?>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input id="password" type="password" name="password" />
                    <?=  generarError('password', $erroresFormulario) ?>
                </div>
                <div>
                    <label for="password2">Reintroduce el password:</label>
                    <input id="password2" type="password" name="password2" />
                    <?=  generarError('password2', $erroresFormulario) ?>
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
