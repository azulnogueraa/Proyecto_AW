<?php

namespace es\ucm\fdi\aw;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye la clase Curso y la configuración necesaria
require_once __DIR__.'/includes/Curso.php';
require_once __DIR__.'/includes/config.php';

// Obtiene la conexión a la base de datos
$conn = Aplicacion::getInstance()->getConexionBd();

// Consulta SQL para seleccionar todos los cursos
$query = "SELECT * FROM Curso";
$result = $conn->query($query);

// Verifica si la consulta fue exitosa
if ($result && $result->num_rows > 0) {
    // Comienza a generar el contenido principal
    $contenidoPrincipal = '<div class="container-cursos">';

    // Itera sobre los resultados de la consulta
    while ($row = $result->fetch_assoc()) {
        // Crea un objeto Curso con los datos de la fila actual
        $curso = new Curso(
            $row['nombre_curso'],
            $row['precio'],
            $row['descripcion']
        );

        // Agrega el curso al contenido principal utilizando el método toBox()
        $contenidoPrincipal .= $curso->toBox();
    }

    // Finaliza el contenedor de cursos
    $contenidoPrincipal .= '</div>';
} else {
    // Si no hay cursos en la base de datos
    $contenidoPrincipal = '<p>No hay cursos disponibles en este momento.</p>';
}

// Incluye la plantilla HTML que muestra el contenido principal
include 'includes/vistas/plantillas/plantilla.php';
?>