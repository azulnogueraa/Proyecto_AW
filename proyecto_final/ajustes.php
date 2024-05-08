<?php
require_once __DIR__.'/includes/src/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tituloPagina = 'Ajustes';
$contenidoPrincipal = '';

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true || $_SESSION['tipo_usuario'] !== es\ucm\fdi\aw\Usuario::ADMIN_ROLE) {
    $contenidoPrincipal .= <<<EOS
        <h1>No eres un administrador!</h1>
        <p>No deberías estar ahí..</p>
    EOS;
} else {
    $contenidoPrincipal .= '<h1 class="panel">Panel de administración</h1>';

    $mensaje = '';

    // Mensajes para el borrado de usuarios
    if (isset($_GET['borrado'])) {
        $borrado = htmlspecialchars($_GET['borrado'], ENT_QUOTES, 'UTF-8');
        $nombre_usuario = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario'], ENT_QUOTES, 'UTF-8') : '';

        if ($borrado === 'exito') {
            $mensaje = "<p>El usuario <strong>{$nombre_usuario}</strong> ha sido borrado exitosamente.</p>";
        } elseif ($borrado === 'error') {
            $mensaje = '<p>Error al intentar borrar el usuario.</p>';
        } elseif ($borrado === 'errorAdmin') {
            $mensaje = '<p>No es una buena idea..</p>';
        } elseif ($borrado === 'errorProfe') {
            $mensaje = "<p>El profesor <strong>{$nombre_usuario}</strong> tiene cursos asignados.</p>";
        } elseif ($borrado === 'errorEstudiante') {
            $mensaje = "<p>El estudiante <strong>{$nombre_usuario}</strong> está inscrito en cursos.</p>";
        }
    }

    // Mensajes para el borrado de cursos
    if (isset($_GET['borrado-curso'])) {
        $borradoCurso = htmlspecialchars($_GET['borrado-curso'], ENT_QUOTES, 'UTF-8');
        $nombre_curso = isset($_GET['nombre_curso']) ? htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8') : '';

        if ($borradoCurso === 'exito') {
            $mensaje = "<p>El curso <strong>{$nombre_curso}</strong> ha sido borrado exitosamente.</p>";
        } elseif ($borradoCurso === 'error') {
            $mensaje = '<p>Error al intentar borrar el curso.</p>';
        } elseif ($_GET['borrado-curso'] === 'error-alumnos') {
            $nombre_curso = isset($_GET['nombre_curso']) ? $_GET['nombre_curso'] : '';
            $mensaje = "<p>El curso <strong>{$nombre_curso}</strong> tiene alumnos inscritos, por lo tanto, no se puede borrar.</p>";
        }
    }

    // Mensajes para cambiar roles de usuarios
    if (isset($_GET['resultado'])) {
        $resultado = htmlspecialchars($_GET['resultado'], ENT_QUOTES, 'UTF-8');
        $nombreUsuario = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario'], ENT_QUOTES, 'UTF-8') : '';
        $nuevoRol = isset($_GET['nuevo_rol']) ? htmlspecialchars($_GET['nuevo_rol'], ENT_QUOTES, 'UTF-8') : '';

        if ($resultado === 'exito') {
            $mensaje = "<p>El rol de <strong>{$nombreUsuario}</strong> ha sido cambiado a <strong>{$nuevoRol}</strong> exitosamente.</p>";
        } elseif ($resultado === 'error_mismo_rol') {
            $mensaje = "<p>El usuario <strong>{$nombreUsuario}</strong> ya tiene asignado el rol {$nuevoRol}.</p>";
        } elseif ($resultado === 'error_profesor_con_cursos') {
            $mensaje = "<p>No se puede cambiar el rol del profesor <strong>{$nombreUsuario}</strong> porque tiene cursos asignados.</p>";
        } elseif ($resultado === 'error_eliminar_usuario_anterior') {
            $mensaje = "<p>Error al eliminar al usuario <strong>{$nombreUsuario}</strong> del rol anterior.</p>";
        } elseif ($resultado === 'error_estudiante_con_cursos') {
            $mensaje = "<p>No se puede cambiar el rol del estudiante <strong>{$nombreUsuario}</strong> porque está inscrito en cursos.</p>";
        }
    }

    // Mensajes para agregar cursos
    if (isset($_GET['agregar_curso'])) {
        $agregar_curso = htmlspecialchars($_GET['agregar_curso'], ENT_QUOTES, 'UTF-8');
        $nombreCursoAgregado = isset($_GET['nombre_curso']) ? htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8') : '';

        if ($agregar_curso === 'exito') {
            $mensaje .= "<p>El curso <strong>{$nombreCursoAgregado}</strong> se ha agregado correctamente.</p>";
        } elseif ($agregar_curso === 'error') {
            $mensaje .= '<p>Error al agregar el curso. Por favor, inténtalo de nuevo.</p>';
        }
    }

    $contenidoPrincipal .= $mensaje;

    if (isset($_SESSION['mensaje'])) {
        $contenidoPrincipal .= "<div class='mensaje'>" . htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8') . "</div>";
        unset($_SESSION['mensaje']);
    }

    /* Administrar Usuarios */

    $contenidoPrincipal .= '<h2 class="panel">1. Administrar Usuarios</h2>';

    // Formulario para borrar usuarios
    $usuarios = es\ucm\fdi\aw\Admin::obtenerUsuarios();
    if (!$usuarios) {
        $contenidoPrincipal .= '<p>Un problema ha ocurrido..</p>';
    } else {
        $seleccionar_usuarios = '';
        foreach ($usuarios as $nombre_usuario) {
            $nombre_usuario = htmlspecialchars($nombre_usuario, ENT_QUOTES, 'UTF-8');
            $seleccionar_usuarios .= "<option value='{$nombre_usuario}'>{$nombre_usuario}</option>";
        }
        $contenidoPrincipal .= <<<EOS
        <div id="contenedor_ajustes" class='container'>
            <h3>Borrar usuarios</h3>
            <form method="POST">
                <label for='usuario'>Selecciona el usuario:</label>
                <select name='usuario' id='usuario'>
                    $seleccionar_usuarios
                </select>
                <button type='submit' name='borrar'>Borrar usuario</button>
            </form>
        </div>
        EOS;
    }

    // Formulario para cambiar el rol de usuario
    $usuarios = es\ucm\fdi\aw\Admin::obtenerUsuarios();
    if ($usuarios) {
        $seleccionar_usuarios = '';
        foreach ($usuarios as $nombre_usuario) {
            $nombre_usuario = htmlspecialchars($nombre_usuario, ENT_QUOTES, 'UTF-8');
            $seleccionar_usuarios .= "<option value='{$nombre_usuario}'>{$nombre_usuario}</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_cambiar_rol" class='container'>
                <h3>Cambiar Rol de Usuario</h3>
                <form method="POST">
                    <label for='usuario'>Selecciona el usuario:</label>
                    <select name='usuario' id='usuario'>
                        $seleccionar_usuarios
                    </select>
                    <label for='nuevo_rol'>Nuevo Rol:</label>
                    <select name='nuevo_rol' id='nuevo_rol'>
                        <option value='Estudiante'>Estudiante</option>
                        <option value='Profesor'>Profesor</option>
                        <option value='Administrador'>Administrador</option>
                    </select>
                    <button type='submit' name='cambiar_rol'>Cambiar Rol</button>
                </form>
            </div>
        EOS;
    } else {
        $contenidoPrincipal .= '<p>No se encontraron usuarios.</p>';
    }

    /* Administrar Cursos */

    $contenidoPrincipal .= '<h2 class="panel">2. Administrar Cursos</h2>';

    // Formulario para agregar cursos
    $contenidoPrincipal .= <<<EOS
        <div id="contenedor_agregar_cursos" class='container'>
            <h3>Agregar Cursos</h3>
            <form action='agregar_curso.php' method='GET'>
                <label for='nombre_curso'>Nombre del curso:</label>
                <input type='text' name='nombre_curso' id='nombre_curso' required>
                <button type='submit'>Agregar Curso</button>
            </form>
        </div>
    EOS;

    // Formulario para editar cursos
    $cursos = es\ucm\fdi\aw\Curso::obtenerNombreCursos();
    if (!$cursos) {
        $contenidoPrincipal .= '<h2>Editar Cursos</h2>';
        $contenidoPrincipal .= '<p>No hay cursos disponibles para editar.</p>';
    } else {
        $seleccionar_cursos = '';
        foreach ($cursos as $nombre_curso) {
            $nombre_curso = htmlspecialchars($nombre_curso, ENT_QUOTES, 'UTF-8');
            $seleccionar_cursos .= "<option value='{$nombre_curso}'>{$nombre_curso}</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_agregar_cursos" class='container'>
                <h3>Editar Cursos</h3>
                <form action='editar_curso.php' method='GET'>
                    <label for='curso'>Selecciona el curso:</label>
                    <select name='nombre_curso' id='curso'>
                        $seleccionar_cursos
                    </select>
                    <button type='submit'>Editar Curso</button>
                </form>
            </div>
        EOS;
    }

    // Formulario para borrar cursos
    $cursos = es\ucm\fdi\aw\Curso::obtenerNombreCursos();
    if (!$cursos) {
        $contenidoPrincipal .= '<h2>Borrar Cursos</h2>';
        $contenidoPrincipal .= '<p>No hay cursos disponibles para borrar.</p>';
    } else {
        $seleccionar_cursos = '';
        foreach ($cursos as $nombre_curso) {
            $nombre_curso = htmlspecialchars($nombre_curso, ENT_QUOTES, 'UTF-8');
            $seleccionar_cursos .= "<option value='{$nombre_curso}'>{$nombre_curso}</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_borrar_cursos" class='container'>
                <h3>Borrar Cursos</h3>
                <form method="POST">
                    <label for='curso'>Selecciona el curso:</label>
                    <select name='curso' id='curso'>
                        $seleccionar_cursos
                    </select>
                    <button type='submit' name='borrar_curso'>Borrar Curso</button>
                </form>
            </div>
        EOS;
    }

}

// Procesar el borrado del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar'], $_POST['usuario'])) {
    $usuario = es\ucm\fdi\aw\Usuario::buscaUsuario(htmlspecialchars($_POST['usuario'], ENT_QUOTES, 'UTF-8'));
    $namespace = 'es\ucm\fdi\aw\\';
    $table = substr(get_class($usuario), strrpos(get_class($usuario), $namespace) + strlen($namespace));
    $admin = es\ucm\fdi\aw\Admin::buscaUsuario($_SESSION['nombre']);

    if ($table == 'Admin') {
        header("Location: ajustes.php?borrado=errorAdmin");
        exit();
    } elseif ($table == 'Estudiante') {
        // Verificar si el estudiante está inscrito en cursos
        $cursosAsignados = $usuario->getCursosAsignados($usuario->getNombreUsuario());
        if (!empty($cursosAsignados)) {
            header("Location: ajustes.php?borrado=errorEstudiante&usuario=" . urlencode($_POST['usuario']));
            exit();
        }

        // Intentar borrar el usuario
        $resultado = $admin->borrarUsuario($table, $usuario);
    } elseif ($table == 'Profesor') {
        // Verificar si el profesor está asociado a cursos
        $cursoDelProfe = $usuario->misCursos();
        if (!empty($cursoDelProfe)) {
            // El profesor tiene cursos asociados
            header("Location: ajustes.php?borrado=errorProfe&usuario=" . urlencode($_POST['usuario']));
            exit();
        }

        // Intentar borrar el usuario
        $resultado = $admin->borrarUsuario($table, $usuario);
    }

    if ($resultado) {
        header("Location: ajustes.php?borrado=exito&usuario=" . urlencode($_POST['usuario']));
        exit();
    } else {
        header("Location: ajustes.php?borrado=error");
        exit();
    }
}

// Procesar el borrado del curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar_curso'], $_POST['curso'])) {
    $curso_a_borrar = htmlspecialchars($_POST['curso'], ENT_QUOTES, 'UTF-8');

    // Verificar si el curso tiene alumnos inscritos
    $curso = es\ucm\fdi\aw\Curso::buscaCursoPorNombre($curso_a_borrar);
    if ($curso && $curso->tieneAlumnosInscritos()) {
        // El curso tiene alumnos inscritos, redirigir con mensaje de error
        header("Location: ajustes.php?borrado-curso=error-alumnos&nombre_curso={$curso_a_borrar}");
        exit();
    }

    // Si no tiene alumnos inscritos, proceder con el borrado
    $resultado = es\ucm\fdi\aw\Curso::borrarCurso($curso_a_borrar);
    if ($resultado) {
        header("Location: ajustes.php?borrado-curso=exito&nombre_curso=" . urlencode($curso_a_borrar));
        exit();
    } else {
        header("Location: ajustes.php?borrado-curso=error");
        exit();
    }
}


// Lógica para cambiar el rol de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_rol'], $_POST['usuario'], $_POST['nuevo_rol'])) {
    $nombreUsuario = htmlspecialchars($_POST['usuario'], ENT_QUOTES, 'UTF-8');
    $nuevoRol = htmlspecialchars($_POST['nuevo_rol'], ENT_QUOTES, 'UTF-8');

    if ($nuevoRol === 'Estudiante' || $nuevoRol === 'Profesor' || $nuevoRol === 'Administrador') {
        $resultado = es\ucm\fdi\aw\Usuario::cambiarRol($nombreUsuario, $nuevoRol);

        if ($resultado === 'error_mismo_rol') {
            header("Location: ajustes.php?resultado=error_mismo_rol&usuario=" . urlencode($nombreUsuario) . "&nuevo_rol=" . urlencode($nuevoRol));
            exit();
        } elseif ($resultado === 'error_profesor_con_cursos') {
            header("Location: ajustes.php?resultado=error_profesor_con_cursos&usuario=" . urlencode($nombreUsuario));
            exit();
        } elseif ($resultado === 'error_eliminar_usuario_anterior') {
            header("Location: ajustes.php?resultado=error_eliminar_usuario_anterior");
            exit();
        } elseif ($resultado === 'exito') {
            // Si $resultado es true, significa que el rol se cambió correctamente
            header("Location: ajustes.php?resultado=exito&usuario=" . urlencode($nombreUsuario) . "&nuevo_rol=" . urlencode($nuevoRol));
            exit();
        } elseif ($resultado === 'error_estudiante_con_cursos') {
            header("Location: ajustes.php?resultado=error_estudiante_con_cursos&usuario=" . urlencode($nombreUsuario));
            exit();
        }
    } else {
        $mensaje = '<p>Rol inválido. Por favor seleccione un rol válido (Estudiante, Profesor, Administrador).</p>';
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
