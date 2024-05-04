<?php
require_once __DIR__ . '/includes/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tituloPagina = 'Perfil de Usuario';
$contenidoPrincipal = '<h1>Perfil de Usuario</h1>';
$contenidoPrincipal .= '<h2>Datos de Usuario</h2>';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario actual
$usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId($_SESSION['id']);

// Obtener cursos asignados al usuario actual
$cursosAsignados = $usuarioActual->getCursosAsignados($usuarioActual->getNombreUsuario());

$nombreUsuario = $usuarioActual->getNombreUsuario();
$apellidoUsuario = $usuarioActual->getApellido();
$emailUsuario = $usuarioActual->getEmail();

if (isset($_GET['actualizado']) && $_GET['actualizado'] === 'true') {
    $contenidoPrincipal .= '<p>Tu perfil actualizado correctamente!</p>';
}

// Contenido principal con formulario de edición
$contenidoPrincipal .= <<<EOS
    <form method="post" action="procesarActualizarPerfil.php">
        <p><strong>Nombre:</strong> <input type="text" name="nombre" value="{$nombreUsuario}" required></p>
        <p><strong>Apellido:</strong> <input type="text" name="apellido" value="{$apellidoUsuario}" required></p>
        <p><strong>Email:</strong> <input type="email" name="email" value="{$emailUsuario}" required></p>
        <input type="submit" name="submit" value="Actualizar">
    </form>

    <h2>Cursos Asignados</h2>
    <ul>
EOS;

foreach ($cursosAsignados as $curso) {
    $nbcurso = $curso->getNombre();
    $contenidoPrincipal .= "<a href='chat.php?nombre_curso=$nbcurso' class='salir-topbar'>{$curso->getNombre()}</a>";
}

$contenidoPrincipal .= '</ul>';

require_once __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
