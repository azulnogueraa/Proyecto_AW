<?php
require_once __DIR__ . '/includes/src/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tituloPagina = 'Perfil de Usuario';
$contenidoPrincipal = '<h1 class="panel">Perfil de Usuario</h1>';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario actual
$usuarioActual = es\ucm\fdi\aw\Usuario::buscaUsuarioPorId($_SESSION['id']);

if (!$usuarioActual) {
    header('Location: login.php');
    exit();
}

// Obtener cursos asignados al usuario actual
$cursosAsignados = $usuarioActual->getCursosAsignados($usuarioActual->getNombreUsuario());

$nombreUsuario = htmlspecialchars($usuarioActual->getNombreUsuario(), ENT_QUOTES, 'UTF-8');
$apellidoUsuario = htmlspecialchars($usuarioActual->getApellido(), ENT_QUOTES, 'UTF-8');
$emailUsuario = htmlspecialchars($usuarioActual->getEmail(), ENT_QUOTES, 'UTF-8');

// Mensaje de confirmación al actualizar el perfil
if (isset($_GET['actualizado']) && $_GET['actualizado'] === 'true') {
    $contenidoPrincipal .= '<p>Tu perfil ha sido actualizado correctamente!</p>';
}

// Formulario de actualización de perfil
$contenidoPrincipal .= <<<EOS
<h2 class="space">Datos de Usuario</h2>
<form method="post" action="procesarActualizarPerfil.php">
    <p><strong>Nombre:</strong> <input type="text" name="nombre" value="{$nombreUsuario}" required></p>
    <p><strong>Apellido:</strong> <input type="text" name="apellido" value="{$apellidoUsuario}" required></p>
    <p><strong>Email:</strong> <input type="email" name="email" value="{$emailUsuario}" required></p>
    <input type="submit" name="submit" value="Actualizar">
</form>

<h2 class="space">Cursos Asignados</h2>
<ul>
EOS;

// Listar cursos asignados
if (!empty($cursosAsignados)) {
    foreach ($cursosAsignados as $curso) {
        $nbcurso = htmlspecialchars($curso->getNombre(), ENT_QUOTES, 'UTF-8');
        $contenidoPrincipal .= "<li><a href='chat.php?nombre_curso={$nbcurso}'>{$nbcurso}</a></li>";
    }
} else {
    $contenidoPrincipal .= '<li>No tienes cursos asignados actualmente.</li>';
}

$contenidoPrincipal .= '</ul>';

require_once __DIR__ . '/includes/vistas/plantillas/plantilla.php';
