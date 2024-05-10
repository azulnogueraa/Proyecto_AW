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
        if (es\ucm\fdi\aw\Usuario::buscaUsuario($nombre)) {
            header('Location: perfil.php?error=usuario_existe');
            exit();
        }
        // Obtener el usuario actual
        $tipo = $_SESSION['tipo_usuario'];
        $tablas = [
            es\ucm\fdi\aw\Usuario::ADMIN_ROLE => "Administrador",
            es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE => "Estudiante",
            es\ucm\fdi\aw\Usuario::PROFESOR_ROLE => "Profesor"
        ];
        $tabla = $tablas[$tipo] ?? "";
        $usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId($tabla, $_SESSION['id']);

        // Actualizar datos del usuario
        $usuarioActual->setNombre($nombre);
        $usuarioActual->setApellido($apellido);
        $usuarioActual->setEmail($email);

        // Guardar cambios en la base de datos
        if (es\ucm\fdi\aw\Usuario::actualizaUsuario($tabla, $usuarioActual)) {
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
