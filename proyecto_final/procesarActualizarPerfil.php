<?php
require_once __DIR__ . '/includes/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];

    // Obtener usuario actual
    $usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId($_SESSION['id']);

    // Actualizar datos del usuario
    $usuarioActual->setNombre($nombre);
    $usuarioActual->setApellido($apellido);
    $usuarioActual->setEmail($email);

    // Guardar cambios en la base de datos
    $usuarioActual->actualizarUsuario();

    // Redirigir de vuelta al perfil con un mensaje de éxito
    header('Location: perfil.php?actualizado=true');
    exit();
} else {
    header('Location: perfil.php?error=true');
    exit();
}
?>
