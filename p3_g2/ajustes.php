<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Ajustes';
$contenidoPrincipal = '';
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true || $_SESSION['tipo_usuario'] !== es\ucm\fdi\aw\Usuario::ADMIN_ROLE) {
    $contenidoPrincipal .= <<<EOS
        <h1>No eres un administrador!</h1>
        <p>No deberías estar ahí..</p>
    EOS;
} else {
    $contenidoPrincipal .= '<h1>Panel de administracion</h1>';

    $mensaje = '';
    if (isset($_GET['borrado'])) {
        if ($_GET['borrado'] === 'exito') {
            $nombre_usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $mensaje = "<p>El usuario <strong>{$nombre_usuario}</strong> ha sido borrado exitosamente.</p>";
        } elseif ($_GET['borrado'] === 'error') {
            $mensaje = '<p>Error al intentar borrar el usuario.</p>';
        } elseif ($_GET['borrado'] === 'errorAdmin') {
            $mensaje = '<p>No es una buena idea..</p>';
        }
    }
    $contenidoPrincipal .= $mensaje;

    $usuarios = es\ucm\fdi\aw\Admin::obtenerUsuarios();
    if (!$usuarios) {
        $contenidoPrincipal .= '<p>Un problema ha ocurrido..</p>';
    } else {
        $seleccionar_usuarios = '';
        foreach($usuarios as $nombre_usuario) {
            $seleccionar_usuarios .= "<option value='" . $nombre_usuario . "'>" . $nombre_usuario . "</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_ajustes" class='container'>
                <h2>Borrar usuario</h2>
                <form action="" method="POST">
                    <label for='usuario'>Selecciona el usuario:</label>
                    <select name='usuario' id='usuario'>
                        $seleccionar_usuarios
                    </select>
                    <button type='submit' name='borrar'>Borrar usuario</button>
                </form>
            </div>
        EOS;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar'], $_POST['usuario'])) {
    $usuario = es\ucm\fdi\aw\Usuario::buscaUsuario($_POST['usuario']);
    $table = get_class($usuario);
    $admin = es\ucm\fdi\aw\Admin::buscaUsuario($_SESSION['nombre']);
    if ($table == 'es\ucm\fdi\aw\Admin') {
        header("Location: ajustes.php?borrado=errorAdmin");
        exit();
    } elseif ($table == 'es\ucm\fdi\aw\Estudiante') {
        $resultado = $admin->borrarUsuario('Estudiante',$usuario);
    } elseif ($table == 'es\ucm\fdi\aw\Profesor') {
        $resultado = $admin->borrarUsuario('Profesor',$usuario);
    }

    if ($resultado) {
        header("Location: ajustes.php?borrado=exito&usuario={$_POST['usuario']}");
        exit();
    } else {
        header("Location: ajustes.php?borrado=error");
        exit();
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';