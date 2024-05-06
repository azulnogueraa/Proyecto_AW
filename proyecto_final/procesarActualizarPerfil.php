<?php
require_once __DIR__ . '/includes/src/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitizar y validar los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $apellido = htmlspecialchars($_POST['apellido'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // Verificar si los datos son válidos
    if ($nombre && $apellido && $email) {
        // Obtener el usuario actual
        $usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId($_SESSION['id']);

        // Actualizar datos del usuario
        $usuarioActual->setNombreUsuario($nombre);
        $usuarioActual->setApellido($apellido);
        $usuarioActual->setEmail($email);

        // Guardar cambios en la base de datos
        if ($usuarioActual->actualizaUsuario()) {
            header('Location: perfil.php?actualizado=true');
            exit();
        } else {
            header('Location: perfil.php?error=actualizacion');
            exit();
        }
    } else {
        // Error en la validación de los datos
        header('Location: perfil.php?error=datos_invalidos');
        exit();
    }
} else {
    header('Location: perfil.php?error=solicitud_invalida');
    exit();
}
