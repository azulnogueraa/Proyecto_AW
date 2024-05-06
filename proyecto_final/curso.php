<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/src/config.php';
require_once __DIR__.'/includes/src/Curso.php';

$tituloPagina = 'Error';
$contenidoPrincipal = '<p>Curso no encontrado.</p>';

// Verificar si se ha proporcionado el nombre del curso en los parámetros GET
if (isset($_GET['nombre_curso'])) {
    $nombre_curso = htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8');

    // Obtener los datos del curso desde la base de datos usando consultas preparadas
    $conn = Aplicacion::getInstance()->getConexionBd();
    $query = "SELECT * FROM Curso WHERE nombre_curso = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nombre_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Obtener la primera fila (debería ser única si se busca por nombre de curso)
        $row = $result->fetch_assoc();

        // Crear un objeto Curso con los datos obtenidos de la base de datos
        $curso = new Curso(
            htmlspecialchars($row['nombre_curso'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['precio'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['duracion'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['nivel_dificultad'], ENT_QUOTES, 'UTF-8')
        );
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
    }
    $stmt->close();
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
