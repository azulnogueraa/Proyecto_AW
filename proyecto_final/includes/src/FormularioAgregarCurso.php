<?php
namespace es\ucm\fdi\aw;

class FormularioAgregarCurso extends Formulario
{
    private $exito;

    public function __construct()
    {
        parent::__construct('formInscripcion');
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombre_curso = htmlspecialchars($datos['nombre_curso'] ?? '', ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($datos['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');
        $duracion = htmlspecialchars($datos['duracion'] ?? '', ENT_QUOTES, 'UTF-8');
        $nivel_dificultad = htmlspecialchars($datos['nivel_dificultad'] ?? '', ENT_QUOTES, 'UTF-8');
        $categoria = htmlspecialchars($datos['categoria'] ?? '', ENT_QUOTES, 'UTF-8');
        $precio = htmlspecialchars($datos['precio'] ?? '', ENT_QUOTES, 'UTF-8');

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $nombresProfesores = Profesor::obtenerNombres();
        $profesorOptions = '';
        foreach ($nombresProfesores as $nombre) {
            $nombreEscapado = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
            $profesorOptions .= "<option value='{$nombreEscapado}'>$nombreEscapado</option>";
        }

        $html = <<<EOF
        <h1>Agregar Curso</h1>
        $htmlErroresGlobales
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
                $profesorOptions
            </select>
        </div>
        <div>
            <label for="duracion">Duración (formato XXh):</label>
            <input id="duracion" type="text" name="duracion" required pattern="\d{1,3}h" value="{$duracion}" />
        </div>
        <div>
            <label for="nivel_dificultad">Nivel de Dificultad:</label>
            <select id="nivel_dificultad" name="nivel_dificultad" required>
                <option value="Principiante" {$this->isSelected('Principiante', $nivel_dificultad)}>Principiante</option>
                <option value="Intermedio" {$this->isSelected('Intermedio', $nivel_dificultad)}>Intermedio</option>
                <option value="Avanzado" {$this->isSelected('Avanzado', $nivel_dificultad)}>Avanzado</option>
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
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre_curso = trim($datos['nombre_curso'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $profesor = trim($datos['profesor'] ?? '');
        $duracion = trim($datos['duracion'] ?? '');
        $nivel_dificultad = trim($datos['nivel_dificultad'] ?? '');
        $categoria = trim($datos['categoria'] ?? '');
        $precio = trim($datos['precio'] ?? '');

        if (empty($nombre_curso)) {
            $this->errores['nombre_curso'] = 'El nombre del curso no puede estar vacío.';
        }

        if (empty($descripcion)) {
            $this->errores['descripcion'] = 'La descripción no puede estar vacía.';
        }

        if (!preg_match('/^\d{1,3}h$/', $duracion)) {
            $this->errores['duracion'] = "La duración debe ser un número seguido de 'h' (por ejemplo, 90h).";
        }

        if (empty($nivel_dificultad)) {
            $this->errores['nivel_dificultad'] = 'El nivel de dificultad no puede estar vacío.';
        }

        if (empty($categoria)) {
            $this->errores['categoria'] = 'La categoría no puede estar vacía.';
        }

        if (!is_numeric($precio) || intval($precio) != $precio) {
            $this->errores['precio'] = 'El precio debe ser un número entero.';
        }

        if (empty($this->errores)) {
            $profesorId = Profesor::obtenerIdPorNombre($profesor);

            if ($profesorId !== null) {
                $resultado = Curso::crearCurso($nombre_curso, $descripcion, $profesorId, $duracion, $nivel_dificultad, $categoria, $precio);

                if ($resultado) {
                    $this->exito = true;
                    header("Location: ajustes.php?agregar_curso=exito&nombre_curso=" . urlencode($nombre_curso));
                    exit();
                } else {
                    $this->errores[] = "Error al insertar el curso en la base de datos.";
                }
            } else {
                $this->errores[] = "Profesor no encontrado en la base de datos.";
            }
        }
    }

    private function isSelected($expectedValue, $currentValue)
    {
        return $expectedValue === $currentValue ? 'selected' : '';
    }

    public function mostrarExito()
    {
        return $this->exito;
    }
}

?>
