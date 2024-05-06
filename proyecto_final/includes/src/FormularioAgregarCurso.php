<?php
namespace es\ucm\fdi\aw;

class FormularioAgregarCurso extends Formulario {

    private $exito;

    public function __construct() {
        parent::__construct('formInscripcion');
    }

    protected function generaCamposFormulario(&$datos) {
        $nombre_curso = $_GET['nombre_curso'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $duracion = $datos['duracion'] ?? '';
        $nivel_dificultad = $datos['nivel_dificultad'] ?? '';
        $categoria = $datos['categoria'] ?? '';
        $precio = $datos['precio'] ?? '';

        $htmlErroresGlobales = $this->generaListaErroresGlobales($this->errores);

        $nombresProfesores = Profesor::obtenerNombres();

        $html = <<<EOF
            <h1>Agregar Curso</h1>
            $htmlErroresGlobales
            <form method="POST">
                <div>
                    <label for="nombre_curso">Nombre del curso:</label>
                    <input id="nombre_curso" type="text" name="nombre_curso" required value="{$nombre_curso}" />
                </div>
                <div>
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50" required>{$descripcion}</textarea>
                </div>
                <div>
                    <label for="profesor">Profesor:</label>
                    <select id="profesor" name="profesor" required>
        EOF;

        foreach ($nombresProfesores as $nombre) {
            $html .= "<option value='{$nombre}' > {$nombre}</option>";
        }

        $html .= <<<EOF
                    </select>
                </div>
                <div>
                    <label for="duracion">Duración (formato XXh):</label>
                    <input id="duracion" type="text" name="duracion" required pattern="\d{1,3}h" value="{$duracion}" />
                </div>
                <div>
                    <label for="nivel_dificultad">Nivel de Dificultad:</label>
                    <select id="nivel_dificultad" name="nivel_dificultad" required>
                        <option value="Principiante" {('Principiante', $nivel_dificultad)}>Principiante</option>
                        <option value="Intermedio" {('Intermedio', $nivel_dificultad)}>Intermedio</option>
                        <option value="Avanzado" {('Avanzado', $nivel_dificultad)}>Avanzado</option>
                    </select>
                </div>
                <div>
                    <label for="categoria">Categoría:</label>
                    <input id="categoria" type="text" name="categoria" required value="{$categoria}" />
                </div>
                <div>
                    <label for="precio">Precio (entero):</label>
                    <input id="precio" type="number" name="precio" required value="{$precio}" />
                </div>
                <div class="boton">
                    <button type="submit">Agregar Curso</button>
                </div>
            </form>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombre_curso = $datos['nombre_curso'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $profesor = $datos['profesor'] ?? '';
        $duracion = $datos['duracion'] ?? '';
        $nivel_dificultad = $datos['nivel_dificultad'] ?? '';
        $categoria = $datos['categoria'] ?? '';
        $precio = $datos['precio'] ?? '';

        // Validar duración con el formato XXh
        if (!preg_match('/^\d{1,3}h$/', $duracion)) {
            $this->errores['duracion'] = "La duración debe ser un número seguido de 'h' (por ejemplo, 90h).";
        }

        // Validar que el precio sea un número entero
        if (!is_numeric($precio) || intval($precio) != $precio) {
            $this->errores['precio'] = "El precio debe ser un número entero.";
        }

        // Si no hay errores, guardar el curso en la base de datos
        if (empty($this->errores)) {
            $profesorId = Profesor::obtenerIdPorNombre($profesor);

            if ($profesorId !== null) {
                $resultado = Curso::crearCurso($nombre_curso, $descripcion, $profesorId, $duracion, $nivel_dificultad, $categoria, $precio);

                if ($resultado) {
                    // Éxito al insertar el curso
                    $this->exito = true;
                    // Redirigir a la página de ajustes con un mensaje de éxito
                    header("Location: ajustes.php?agregar_curso=exito&nombre_curso={$nombre_curso}");
                } else {
                    // Error al insertar el curso
                    $this->errores[] = "Error al insertar el curso en la base de datos.";
                    // Redirigir a la página de ajustes con un mensaje de error
                    header("Location: ajustes.php?agregar_curso=error");
                }
            } else {
                // Profesor no encontrado o ID no válido
                $this->errores[] = "Profesor no encontrado en la base de datos.";
            }
        }
    }

    public function mostrarExito() {
        return $this->exito;
    }
}
?>
