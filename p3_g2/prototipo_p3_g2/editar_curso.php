<?php
require_once __DIR__ . '/includes/config.php';

ini_set("display_errors", 1);
error_reporting(E_ALL);


$tituloPagina = 'Editar Curso';
$contenidoPrincipal = '';

// Verificar si se proporcionó un nombre de curso en la solicitud GET
if (isset($_GET['nombre_curso'])) {
    $nombreCurso = $_GET['nombre_curso'];

    try {
        // Intenta obtener los datos del curso para editar
        $curso = es\ucm\fdi\aw\Curso::editarCurso($nombreCurso);

        // Verificar si se encontró el curso
        if ($curso) {
            // Construir el formulario de edición con los datos del curso
            $contenidoPrincipal .= <<<EOS
            <div id="contenedor_editar_curso">
                <h2>Editar Curso</h2>
                <form action="actualizar_curso.php" method="POST">
                    <label for="nombre_curso">Nombre:</label>
                    <input type="text" id="nombre_curso" name="nombre_curso" value="{$curso['nombre_curso']}" readonly><br><br>
                    
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50">{$curso['descripcion']}</textarea><br><br>
            
                    <label for="duracion">Duración:</label>
                    <input type="text" id="duracion" name="duracion" value="{$curso['duracion']}" required><br><br>
                    
                    <label for="nivel_dificultad">Nivel de Dificultad:</label>
                    <select id="nivel_dificultad" name="nivel_dificultad" required>
                        <option value="Principiante" {$curso['nivel_dificultad']} == 'Principiante' ? 'selected' : ''; >Principiante</option>
                        <option value="Intermedio" {$curso['nivel_dificultad']} == 'Intermedio' ? 'selected' : ''; >Intermedio</option>
                        <option value="Avanzado" {$curso['nivel_dificultad']} == 'Avanzado' ? 'selected' : ''; >Avanzado</option>
                    </select><br><br>
                    
                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" value="{$curso['categoria']}" required><br><br>
                    
                    <label for="precio">Precio:</label>
                    <input type="text" id="precio" name="precio" value="{$curso['precio']}" required><br><br>
                    
                    <button type="submit" name="editar">Actualizar Curso</button>
                </form>
            </div>
            EOS;
        } else {
            // Curso no encontrado, mostrar mensaje de error
            $contenidoPrincipal .= '<p>Curso no encontrado.</p>';
        }
    } catch (\Exception $e) {
        // Capturar excepciones y mostrar mensaje de error
        $contenidoPrincipal .= '<p>Error: ' . $e->getMessage() . '</p>';
    }
} else {
    // Nombre de curso no especificado en la URL, redirigir o mostrar mensaje de error
    $contenidoPrincipal .= '<p>Nombre de curso no especificado.</p>';
}

// Incluir la plantilla para mostrar la página
require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
