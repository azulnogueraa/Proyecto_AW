<?php
require_once __DIR__.'/includes/src/config.php';

// Verificar si se ha proporcionado el nombre del curso en los parámetros GET
if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_GET['nombre_curso'])) {
    $nombre_curso = htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8');

    // Obtener el curso
    $curso = es\ucm\fdi\aw\Curso::buscaCursoPorNombre($nombre_curso);
    if ($curso) {
        $tituloPagina = $curso->getNombre();
        $contenidoPrincipal = <<<EOS
        <div id="contenedor_vista_curso"> 
            <div id="main_curso">
                <h1> {$curso->getNombre()} </h1>
                <ul>
                    <li><p> Categoría: {$curso->getCategoria()} </p></li>
                    <li><p> {$curso->getDescripcion()} </p></li>
                    <li><p> Duración: {$curso->getDuracion()} </p></li>
                    <li><p> Nivel de dificultad: {$curso->getNivelDificultad()} </p></li>
                </ul><br>
        EOS;

        // Cambiar el contenidoPrincipal en función del tipo de usuario y si está inscrito o no al curso
        if($_SESSION['tipo_usuario'] === es\ucm\fdi\aw\Usuario::ADMIN_ROLE) {
            $contenidoPrincipal .= esInscrito(false, $nombre_curso);
        } elseif($_SESSION['tipo_usuario'] === es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE) {
            if(es\ucm\fdi\aw\Registrado::esRegistrado($_SESSION['id'], $curso->getNombre())) {
                $contenidoPrincipal .= esInscrito(true, $nombre_curso);
            } else {
                $contenidoPrincipal .= noInscrito($curso);
            }
        } elseif($_SESSION['tipo_usuario'] === es\ucm\fdi\aw\Usuario::PROFESOR_ROLE) {
            if($curso->getProfesorId() == $_SESSION['id']) {
                $contenidoPrincipal .= esInscrito(false, $nombre_curso);
            } else {
                $tituloPagina = 'Error';
                $contenidoPrincipal = '<h1>No tienes permisos para ver este curso.</h1>';
            }
        }

    } else {
         // Si no se encuentra el curso en la base de datos
        $tituloPagina = 'Error';
        $contenidoPrincipal = '<h1>Curso no encontrado.</h1>';
    }
} else {
    // Si no se proporciona el nombre del curso en los parámetros GET o no es conectado
    header('Location: index.php'); // Redirigir a la página principal
    exit();
}

/**
 * ContenidoPrincipal si el usuario es inscrito al curso
 * @param $esEstudiante bool que indica si el usuario es estudiante
 * @param $nombre_curso Nombre del curso
 */
function esInscrito($esEstudiante, $nombre_curso) {
    $contenido = "";
    if ($esEstudiante) {
        //Posibilidad de darse de baja
        $contenido .= <<<EOS
        <form action="darseDeBaja.php" method="POST">
            <input type="hidden" name="nombre_curso" value="{$nombre_curso}">
            <button type="submit" class="button-curso">Darse de baja</button>
        </form>
        EOS;
    }
    $contenido .= <<<EOS
        </div>
        <div id="chat-container">
            <div id="chat">
            </div>
            <div id="chat-input">
                <input type="text" id="mensaje" placeholder="Escribe un mensaje...">
                <button id="validar">&#x2714;</button>
            </div>
        </div>
    </div>
    <script src="JS/chat.js"></script>
    EOS;
    return $contenido;
}

/**
 * ContenidoPrincipal si el usuario no es inscrito al curso
 * @param $curso Curso al que el usuario no está inscrito
 */
function noInscrito($curso) {
    $nombre_curso = urlencode($curso->getNombre());
    $contenido = <<<EOS
    <h3> {$curso->getPrecio()} EUR </h3><br>
    <a href='inscripcion.php?nombre_curso={$nombre_curso}' class='button-curso'>Inscribirse</a>
    </div>
    </div>
    EOS;
    return $contenido;
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
