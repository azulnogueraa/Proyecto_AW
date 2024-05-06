<?php
require_once __DIR__ . '/includes/src/config.php';

ini_set("display_errors", 1);
error_reporting(E_ALL);

$tituloPagina = 'Editar Curso';
$contenidoPrincipal = '';

// Verificar si se proporcionó un nombre de curso en la solicitud GET
if (isset($_GET['nombre_curso'])) {
    $nombreCurso = htmlspecialchars($_GET['nombre_curso'], ENT_QUOTES, 'UTF-8');

    try {
        // Intenta obtener los datos del curso para editar
        $curso = es\ucm\fdi\aw\Curso::editarCurso($nombreCurso);

        // Verificar si se encontró el curso
        if ($curso) {
            // Escapar cada campo individualmente para evitar XSS
            $nombreCursoEscapado = htmlspecialchars($curso['nombre_curso'], ENT_QUOTES, 'UTF-8');
            $descripcionEscapada = htmlspecialchars($curso['descripcion'], ENT_QUOTES, 'UTF-8');
            $duracionEscapada = htmlspecialchars($curso['duracion'], ENT_QUOTES, 'UTF-8');
            $nivelDificultadEscapado = htmlspecialchars($curso['nivel_dificultad'], ENT_QUOTES, 'UTF-8');
            $categoriaEscapada = htmlspecialchars($curso['categoria'], ENT_QUOTES, 'UTF-8');
            $precioEscapado = htmlspecialchars($curso['precio'], ENT_QUOTES, 'UTF-8');

            // Generar las opciones del campo `nivel_dificultad`
            function generarOpcion($nivel, $nivelActual) {
                $selected = ($nivel === $nivelActual) ? 'selected' : '';
                return "<option value='$nivel' $selected>$nivel</option>";
            }

            $opcionesNivelDificultad = generarOpcion('Principiante', $nivelDificultadEscapado)
                . generarOpcion('Intermedio', $nivelDificultadEscapado)
                . generarOpcion('Avanzado', $nivelDificultadEscapado);

            // Construir el formulario de edición con los datos del curso
            $contenidoPrincipal .= <<<EOS
            <div id="contenedor_editar_curso">
                <h2>Editar Curso</h2>
                <form action="actualizar_curso.php" method="POST">
                    <label for="nombre_curso">Nombre:</label>
                    <input type="text" id="nombre_curso" name="nombre_curso" value="$nombreCursoEscapado" readonly><br><br>
                    
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50">$descripcionEscapada</textarea><br><br>
            
                    <label for="duracion">Duración:</label>
                    <input type="text" id="duracion" name="duracion" value="$duracionEscapada" required><br><br>
                    
                    <label for="nivel_dificultad">Nivel de Dificultad:</label>
                    <select id="nivel_dificultad" name="nivel_dificultad" required>
                        $opcionesNivelDificultad
                    </select><br><br>
                    
                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" value="$categoriaEscapada" required><br><br>
                    
                    <label for="precio">Precio:</label>
                    <input type="text" id="precio" name="precio" value="$precioEscapado" required><br><br>
                    
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
