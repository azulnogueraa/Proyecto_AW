<?php
require_once __DIR__.'/includes/src/config.php';

// Verificar si se ha proporcionado el nombre del curso en los parámetros GET
if (isset($_GET['nombre_curso'])) {
    $nombre_curso = htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8');

    // Obtener el curso
    $curso = es\ucm\fdi\aw\Curso::buscaCursoPorNombre($nombre_curso);
    if ($curso) {
        $tituloPagina = $curso->getNombre();
         //TODO changer la vue suivant que l'utilisateur soit inscrit au cours (afficher chat et enlever inscribirse) ou non (enlever chat)
         //TODO Si es un admin, hacer como si fue inscrito 
         //TODO Si es un estudiante, hacer los dos casos (inscrito o no inscrito)
         if(isset($_SESSION['login']) && $_SESSION['login']) {
            if($_SESSION['tipo_usuario'] === es\ucm\fdi\aw\Usuario::ESTUDIANTE_ROLE) {
                if(es\ucm\fdi\aw\Registrado::esRegistrado($_SESSION['id'], $curso->getNombre())) {
                //TODO
                } else {
                //TODO
                }
            }
         }
         //TODO Si es un profesor, no se...
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
                <h3> {$curso->getPrecio()} EUR </h3><br>
                <a href='inscripcion.php?nombre_curso={$curso->getNombre()}' class='button-curso'>Inscribirse</a>
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
    } else {
         // Si no se encuentra el curso en la base de datos
        $tituloPagina = 'Error';
        $contenidoPrincipal = '<h1>Curso no encontrado.</h1>';
    }
} else {
    // Si no se proporciona el nombre del curso en los parámetros GET
    header('Location: index.php'); // Redirigir a la página principal
    exit();
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
