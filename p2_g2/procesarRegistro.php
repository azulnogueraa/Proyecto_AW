<?php
//Inicio del procesamiento
session_start();

$formEnviando = isset($_POST["registro"]);
if (! $formEnviando) {
    header('Location: registro.php');
    exit();
}

require_once __DIR__.'/includes/utils.php';

$erroresFormulario = [];
/*
$nombreUsuario = filter_input(INPUT_POST,'nombreUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $nombreUsuario || empty($nombreUsuario=trim($nombreUsuario)) || mb_strlen($nombreUsuario) < 3) {
	$erroresFormulario['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
}

$apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $apellido || empty($nombre=trim($apellido)) || mb_strlen($apellido) < 3) {
	$erroresFormulario['apellido'] = 'El apellido tiene que tener una longitud de al menos 3 caracteres.';
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//if ( ! $email || empty($nombre=trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//	$erroresFormulario['email'] = 'Introduce una dirección de correo electrónico válida.';
//}
$domain = substr($email, strpos($email, '@') + 1);

if (strpos($domain, 'estudiante') !== false) {
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
}*/

if (count($erroresFormulario) == 0) {
    

    $username = $_POST["username"];
    $apellido = $_POST["apellido"];
    $contrasena = $_POST["password"];
    $email = $_POST["email"];
    // Add more variables for other form fields as needed

    // Database connection parameters
    $servername = "localhost";
    $database = "your_database";
    $username_db = "your_username";
    $password_db = "your_password";

    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username_db, $password_db);

    $sqlFile = file_get_contents('database.sql');
    $conn->exec($sqlFile);

    // Read and execute SQL commands from the SQL file
    $stmt = $conn->prepare("INSERT INTO Usuarios (id, nombre_usuario, apellido, email, contrasena) VALUES ( 1, :username, :apellido, :email, :contrasena)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':contrasena', $contrasena);
    // Bind more parameters for other form fields as needed

    // Execute the statement
    $stmt->execute();

    // Close the connection
    $conn = null;

    // Redirect or display a success message as needed
    header("Location: success.php");
    exit();
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
    </head>
    <body>
        <?php require "includes/vistas/comun/topbar.php"; ?>
        <main>
            <article>
                <h1>Registro de usuario</h1>
                <?= generaErroresGlobalesFormulario($erroresFormulario) ?>
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