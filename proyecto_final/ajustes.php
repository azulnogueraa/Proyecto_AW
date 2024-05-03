<?php
require_once __DIR__.'/includes/config.php';

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
        } elseif ($_GET['borrado'] === 'errorProfe') {
            $mensaje = '<p>Este profesor propone cursos..</p>';
        }
    }
    // Verificar si hay mensaje específico para borrado de curso
    if (isset($_GET['borrado-curso'])) {
        if ($_GET['borrado-curso'] === 'exito') {
            $nombre_curso = isset($_GET['nombre_curso']) ? $_GET['nombre_curso'] : '';
            $mensaje = "<p>El curso <strong>{$nombre_curso}</strong> ha sido borrado exitosamente.</p>";
        } elseif ($_GET['borrado-curso'] === 'error') {
            $mensaje = '<p>Error al intentar borrar el curso.</p>';
        }
    }

    if (isset($_GET['resultado'])) {
        if ($_GET['resultado'] === 'exito') {
            $nombreUsuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $nuevoRol = isset($_GET['nuevo_rol']) ? $_GET['nuevo_rol'] : '';
            $mensaje = "<p>El rol de <strong>{$nombreUsuario}</strong> ha sido cambiado a <strong>{$nuevoRol}</strong> exitosamente.</p>";
        } elseif ($_GET['resultado'] === 'error_mismo_rol') {
            $nombreUsuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $nuevoRol = isset($_GET['nuevo_rol']) ? $_GET['nuevo_rol'] : '';
            $mensaje = "<p>El usuario <strong>{$nombreUsuario}</strong> ya tiene asignado el rol {$nuevoRol}.</p>";
        } elseif ($_GET['resultado'] === 'error_profesor_con_cursos') {
            $nombreUsuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $mensaje = "<p>No se puede cambiar el rol del profesor <strong>{$nombreUsuario}</strong> porque tiene cursos asignados.</p>";
        } elseif ($_GET['resultado'] === 'error_eliminar_usuario_anterior') {
            $nombreUsuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $mensaje = "<p>Error al eliminar al usuario <strong>{$nombreUsuario}</strong> del rol anterior.</p>";
        }
        elseif ($_GET['resultado'] === 'error_estudiante_con_cursos') {
            $nombreUsuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            $mensaje = "<p>No se puede cambiar el rol del estudiante <strong>{$nombreUsuario}</strong> porque esta inscripto en cursos.</p>";
        }
    }

    if (isset($_GET['agregar_curso'])) {
        if ($_GET['agregar_curso'] === 'exito') {
            $nombreCursoAgregado = isset($_GET['nombre_curso']) ? $_GET['nombre_curso'] : '';
            $mensaje .= "<p>El curso <strong>{$nombreCursoAgregado}</strong> se ha agregado correctamente.</p>";
        } elseif ($_GET['agregar_curso'] === 'error') {
            $mensaje .= '<p>Error al agregar el curso. Por favor, inténtalo de nuevo.</p>';
        }
    }
    

    $contenidoPrincipal .= $mensaje;

    if (isset($_SESSION['mensaje'])) {
        $contenidoPrincipal .= "<div class='mensaje'>{$_SESSION['mensaje']}</div>";
        unset($_SESSION['mensaje']); 
    }

    /*                                          ADMINISTRAR USUARIOS                                        */

    $contenidoPrincipal .= '<h1>1. Administrar Usuarios</h1>';

    //Formulario para borrar usuarios
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
            <h2>Borrar usuarios</h2>
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

    //Formulario para cambiar el rol de usuario
    $usuarios = es\ucm\fdi\aw\Admin::obtenerUsuarios();
    if ($usuarios) {
        $seleccionar_usuarios = '';
        foreach($usuarios as $nombre_usuario) {
            $seleccionar_usuarios .= "<option value='" . $nombre_usuario . "'>" . $nombre_usuario . "</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_cambiar_rol" class='container'>
                <h2>Cambiar Rol de Usuario</h2>
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

    /*                                          ADMINISTRAR CURSOS                                        */

    $contenidoPrincipal .= '<h1>2. Administrar Cursos</h2>';

    //Formulario para agregar cursos
    $contenidoPrincipal .= <<<EOS
        <div id="contenedor_agregar_cursos" class='container'>
            <h2>Agregar Cursos</h2>
            <form action='agregar_curso.php' method='GET'>
                <label for='nombre_curso'>Nombre del curso:</label>
                <input type='text' name='nombre_curso' id='nombre_curso' required>
                <button type='submit'>Agregar Curso</button>
            </form>
        </div>
    EOS;

    //Formulario para editar cursos
    $cursos = es\ucm\fdi\aw\Curso::obtenerNombreCursos(); //el nombre del metodo puede cambiar
    if (!$cursos) {
        $contenidoPrincipal .= '<h2>Editar Cursos</h2>';
        $contenidoPrincipal .= '<p>No hay cursos disponibles para editar.</p>';
    } else {
        $seleccionar_cursos = '';
        foreach($cursos as $nombre_curso) {
            $seleccionar_cursos .= "<option value='" . $nombre_curso . "'>" . $nombre_curso . "</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <h2>Editar Cursos</h2>
            <form action='editar_curso.php' method='GET'>
                <label for='curso'>Selecciona el curso:</label>
                <select name='nombre_curso' id='curso'>
                    $seleccionar_cursos
                </select>
                <button type='submit'>Editar Curso</button>
            </form>
        EOS;
    }

    //Formulario para borrar cursos
    $cursos = es\ucm\fdi\aw\Curso::obtenerNombreCursos(); // Obtener cursos disponibles
    if (!$cursos) {
        $contenidoPrincipal .= '<h2>Borrar Cursos</h2>';
        $contenidoPrincipal .= '<p>No hay cursos disponibles para borrar.</p>';
    } else {
        $seleccionar_cursos = '';
        foreach ($cursos as $nombre_curso) {
            $seleccionar_cursos .= "<option value='" . htmlspecialchars($nombre_curso) . "'>" . htmlspecialchars($nombre_curso) . "</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <div id="contenedor_borrar_cursos" class='container'>
                <h2>Borrar Cursos</h2>
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
    $usuario = es\ucm\fdi\aw\Usuario::buscaUsuario($_POST['usuario']);
    $namespace = 'es\ucm\fdi\aw\\';
    $table = substr(get_class($usuario), strrpos(get_class($usuario), $namespace) + strlen($namespace));
    $admin = es\ucm\fdi\aw\Admin::buscaUsuario($_SESSION['nombre']);
    if ($table == 'Admin') {
        header("Location: ajustes.php?borrado=errorAdmin");
        exit();
    } elseif ($table == 'Estudiante') {
        $resultado = $admin->borrarUsuario($table,$usuario);
    } elseif ($table == 'Profesor') {
        $cursoDelProfe = $usuario->misCursos();
        if (!$cursoDelProfe) { //El profe no esta en ningun curso : podemos eliminarlo
            $resultado = $admin->borrarUsuario($table,$usuario);
        } else { //De momento vamos a decir que no se puede borrar el profe si tiene cursos
            header("Location: ajustes.php?borrado=errorProfe");
            exit();
        }  
    }

    if ($resultado) {
        header("Location: ajustes.php?borrado=exito&usuario={$_POST['usuario']}");
        exit();
    } else {
        header("Location: ajustes.php?borrado=error");
        exit();
    }
}

// Procesar el borrado del curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar_curso'], $_POST['curso'])) {
    $curso_a_borrar = $_POST['curso'];

    // Lógica para borrar el curso
    $resultado = es\ucm\fdi\aw\Curso::borrarCurso($curso_a_borrar);
    if ($resultado) {
        header("Location: ajustes.php?borrado-curso=exito&nombre_curso={$curso_a_borrar}");
        exit();
    } else {
        header("Location: ajustes.php?borrado-curso=error");
        exit();
    }
}

// Lógica para cambiar el rol de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_rol'], $_POST['usuario'], $_POST['nuevo_rol'])) {
    $nombreUsuario = $_POST['usuario'];
    $nuevoRol = $_POST['nuevo_rol'];

    if ($nuevoRol === 'Estudiante' || $nuevoRol === 'Profesor' || $nuevoRol === 'Administrador') {
        $resultado = es\ucm\fdi\aw\Usuario::cambiarRol($nombreUsuario, $nuevoRol);
    
        if ($resultado === 'error_mismo_rol') {
            header("Location: ajustes.php?resultado=error_mismo_rol&usuario={$nombreUsuario}&nuevo_rol={$nuevoRol}");
            exit();
        } elseif ($resultado === 'error_profesor_con_cursos') {
            header("Location: ajustes.php?resultado=error_profesor_con_cursos&usuario={$nombreUsuario}");
            exit();
        } elseif ($resultado === 'error_eliminar_usuario_anterior') {
            header("Location: ajustes.php?resultado=error_eliminar_usuario_anterior");
            exit();
        } elseif ($resultado === 'exito') {
            // Si $resultado es true, significa que el rol se cambió correctamente
            header("Location: ajustes.php?resultado=exito&usuario={$nombreUsuario}&nuevo_rol={$nuevoRol}");
            exit();
        } elseif ($resultado === 'error_estudiante_con_cursos') {
            header("Location: ajustes.php?resultado=error_estudiante_con_cursos&usuario={$nombreUsuario}");
            exit();
        }
    
    } else {
        $mensaje = '<p>Rol inválido. Por favor seleccione un rol válido (Estudiante, Profesor, Administrador).</p>';
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';