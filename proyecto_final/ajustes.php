<?php
require_once __DIR__.'/includes/config.php';

// Borrar
ini_set('log_errors',1);
ini_set('error_log','error.log');

ini_set('display_errors', 1);



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

    $contenidoPrincipal .= $mensaje;

    if (isset($_SESSION['mensaje'])) {
        $contenidoPrincipal .= "<div class='mensaje'>{$_SESSION['mensaje']}</div>";
        unset($_SESSION['mensaje']); 
    }

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

    $cursos = es\ucm\fdi\aw\Curso::obtenerNombreCursos(); //el nombre del metodo puede cambiar
    if (!$cursos) {
        $contenidoPrincipal .= '<h2>Administrar Cursos</h2>';
        $contenidoPrincipal .= '<p>No hay cursos disponibles para editar.</p>';
    } else {
        $seleccionar_cursos = '';
        foreach($cursos as $nombre_curso) {
            $seleccionar_cursos .= "<option value='" . $nombre_curso . "'>" . $nombre_curso . "</option>";
        }
        $contenidoPrincipal .= <<<EOS
            <h2>Administrar Cursos</h2>
            <form action='editar_curso.php' method='GET'>
                <label for='curso'>Selecciona el curso:</label>
                <select name='nombre_curso' id='curso'>
                    $seleccionar_cursos
                </select>
                <button type='submit'>Editar Curso</button>
            </form>
        EOS;
    }
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

    // // Lógica para cambiar el rol de usuario
    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_rol'], $_POST['usuario'], $_POST['nuevo_rol'])) {
    //     $nombreUsuario = $_POST['usuario'];
    //     $nuevoRol = $_POST['nuevo_rol'];

    //     // Validar que el nuevo rol sea un rol válido (Estudiante, Profesor, Administrador)
    //     if ($nuevoRol === 'Estudiante' || $nuevoRol === 'Profesor' || $nuevoRol === 'Administrador') {
    //         $resultado = es\ucm\fdi\aw\Usuario::cambiarRol($nombreUsuario, $nuevoRol);
    //         if ($resultado) {
    //             $mensaje = "<p>El rol de <strong>{$nombreUsuario}</strong> ha sido cambiado a <strong>{$nuevoRol}</strong> exitosamente.</p>";
    //         } else {
    //             $mensaje = '<p>Error al intentar cambiar el rol del usuario.</p>';
    //         }
    //     } else {
    //         $mensaje = '<p>Rol inválido. Por favor seleccione un rol válido (Estudiante, Profesor, Administrador).</p>';
    //     }

    //     $contenidoPrincipal .= $mensaje;
    // }

    Formulario para cambiar el rol de usuario
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

}
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_rol'])) {
    // Asumiendo que 'usuario' y 'nuevo_rol' son enviados a través del formulario POST
    $nombreUsuario = $_POST['usuario'];
    $nuevoRol = $_POST['nuevo_rol'];
    $mensaje = es\ucm\fdi\aw\Usuario::cambiarRol($nombreUsuario, $nuevoRol);
    
    // Muestra el mensaje al usuario
    // Puedes almacenarlo en la sesión y redirigir o mostrarlo directamente si estás manejando la petición de forma síncrona
    $_SESSION['mensaje'] = $mensaje;
    header("Location: ruta_donde_se_muestra_el_mensaje.php");
    exit();
}



require __DIR__.'/includes/vistas/plantillas/plantilla.php';