<?php
require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Perfil de Usuario';
$contenidoPrincipal = '';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario actual
$usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuario($_SESSION['nombre']);

// Obtener cursos asignados al usuario actual
$cursosAsignados = $usuarioActual->getCursosAsignados($usuarioActual->getNombreUsuario());

$nombreUsuario = $usuarioActual->getNombreUsuario();
$apellidoUsuario = $usuarioActual->getApellido();
$emailUsuario = $usuarioActual->getEmail();

// Contenido principal
$contenidoPrincipal .= <<<EOS
    <h1>Perfil de Usuario</h1>
    <h2>Datos de Usuario</h2>
    <p><strong>Nombre:</strong> {$nombreUsuario}</p>
    <p><strong>Apellido:</strong> {$apellidoUsuario}</p>
    <p><strong>Email:</strong> {$emailUsuario}</p>

    <h2>Cursos Asignados</h2>
    <ul>
EOS;

foreach ($cursosAsignados as $curso) {
    $contenidoPrincipal .= "<li>{$curso->getNombre()}</li>";
}

$contenidoPrincipal .= '</ul>';

require_once __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
