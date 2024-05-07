<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/src/config.php';
require_once __DIR__.'/includes/src/Curso.php';

// Verificar si se ha proporcionado el nombre del curso en los parámetros GET
if (isset($_GET['nombre_curso'])) {
    $nombre_curso = $_GET['nombre_curso'];

    // Obtener los datos del curso desde la base de datos
    $conn = Aplicacion::getInstance()->getConexionBd();
    $query = "SELECT * FROM Curso WHERE nombre_curso = '$nombre_curso'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        
        // Obtener la primera fila (debería ser única si se busca por nombre de curso)
        $row = $result->fetch_assoc();

        // Crear un objeto Curso con los datos obtenidos de la base de datos
        $curso = new Curso(
            $row['nombre_curso'],
            $row['precio'],
            $row['descripcion'],
            $row['duracion'],
            $row['categoria'],
            $row['nivel_dificultad']
        );
        $tituloPagina = $curso->getNombre();
         //TODO changer la vue suivant que l'utilisateur soit inscrit au cours (afficher chat et enlever inscribirse) ou non (enlever chat)
         //TODO dans Perfil : Que le lien envoie vers curso en etant connecté et plus a chat.php
        $contenidoPrincipal = <<<EOS
        <div id=contenedor_vista_curso> 
            <div id=main_curso>
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
        $contenidoPrincipal = '<p>Curso no encontrado.</p>';
    }
} else {
    // Si no se proporciona el nombre del curso en los parámetros GET
    header('Location: index.php'); // Redirigir a la página principal
    exit();
}

// Incluir la plantilla HTML que mostrará el contenido principal
include 'includes/vistas/plantillas/plantilla.php';