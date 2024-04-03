<?php
session_start();

// Mostrar errores de PHP en la página web
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se enviaron los datos del formulario
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Conectar a la base de datos
    require_once 'includes/utils.php'; // Asegúrate de que este archivo contenga la función conexionBD()

    $conn = conexionBD();

    // Consulta SQL para verificar si el usuario es un estudiante
    // Consulta SQL para obtener el usuario con el nombre de usuario proporcionado
    $query = sprintf("SELECT * FROM Estudiante WHERE nombre_usuario = '%s' AND contrasena = '%s'", $conn->real_escape_string($username), $conn->real_escape_string($password));
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Usuario es un estudiante, iniciar sesión
        $_SESSION['login'] = true;
        $_SESSION['nombre'] = $username;
        $_SESSION['tipo_usuario'] = 'Estudiante';
        header('Location: index.php');
        exit();
    } else {
        // El usuario no es un estudiante, verificar si es administrador
        $query = sprintf("SELECT * FROM Administrador WHERE nombre_usuario = '%s' AND contrasena = '%s'", $conn->real_escape_string($username), $conn->real_escape_string($password));
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            // Usuario es un administrador, iniciar sesión
            $_SESSION['login'] = true;
            $_SESSION['esAdmin'] = true;
            $_SESSION['nombre'] = $username;
            $_SESSION['tipo_usuario'] = 'Administrador';
            header('Location: index.php');
            exit();
        } else {
            // El usuario no es un administrador, verificar si es profesor
            $query = sprintf("SELECT * FROM Profesor WHERE nombre_usuario = '%s' AND contrasena = '%s'", $conn->real_escape_string($username), $conn->real_escape_string($password));
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                // Usuario es un profesor, iniciar sesión
                $_SESSION['login'] = true;
                $_SESSION['esProfesor'] = true;
                $_SESSION['nombre'] = $username;
                $_SESSION['tipo_usuario'] = 'Profesor';
                header('Location: index.php');
                exit();
            } else {
                // El usuario no es un profesor
                $error = "Usuario o contraseña incorrectos.";
            }
        }
    }
} else {
    // Los datos del formulario no fueron enviados
    $error = "Por favor, introduce tu nombre de usuario y contraseña.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/topBar.css">
</head>
<body>
    <!-- topbar -->
    <?php require "includes/vistas/comun/topbar.php"; ?>

    <div class="texto"> 
        <?php
        if (isset($error)) {
            // Mostrar mensaje de error
            echo "<h1>ERROR</h1>";
            echo "<p>$error</p>";
        } elseif (isset($_SESSION['login'])) {
            // Usuario autenticado, redirigir al inicio
            echo "<h1>Bienvenido {$_SESSION['tipo_usuario']}</h1>";
        }
        ?>
    </div>
</body>
</html>
