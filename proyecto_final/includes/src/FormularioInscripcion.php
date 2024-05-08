<?php
namespace es\ucm\fdi\aw;

class FormularioInscripcion extends Formulario {

    private $nombre_curso;

    public function __construct($nombre_curso) {
        parent::__construct('formInscripcion', ['urlRedireccion' => 'index.php']);
        $this->nombre_curso = $nombre_curso;
    }    

    protected function generaCamposFormulario(&$datos) {
        // Obtener datos del usuario actual si está autenticado
        $nombre_usuario = '';
        $apellido = '';
        $email = '';
        if (isset($_SESSION['login']) && $_SESSION['login']) {
            $usuarioActual = Usuario::buscaUsuario($_SESSION['nombre']);
            $nombre_usuario = $usuarioActual->getNombreUsuario();
            $apellido = $usuarioActual->getApellido();
            $email = $usuarioActual->getEmail();
        }

        $nombre_curso = $this->nombre_curso;

        // Obtener profesores disponibles
        $profesores = $this->obtenerProfesoresDisponibles();

        // Construir opciones de selección de profesores
        $optionsProfesores = '';
        foreach ($profesores as $profesor) {
            $optionsProfesores .= "<option value=\"{$profesor['id']}\">{$profesor['nombre_usuario']} {$profesor['apellido']}</option>";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            <h1>Formulario de Inscripción</h1>
            $htmlErroresGlobales
            <fieldset>
                <div class="legenda">
                    <legend>1. Datos para la inscripción</legend>
                </div>
                <div>
                    <label for="nombre_usuario">Nombre de usuario:</label>
                    <input id="nombre_usuario" type="text" name="nombre_usuario" required value="{$nombre_usuario}" readonly/>
                </div>
                <div>
                    <label for="nombre_curso">Nombre del curso:</label>
                    <input id="nombre_curso" type="text" name="nombre_curso" value="$nombre_curso" readonly/>
                </div>
                <div>
                    <label for="apellido">Apellido:</label>
                    <input id="apellido" type="text" name="apellido" required value="{$apellido}" readonly/>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" required value="{$email}" readonly/>
                </div>
                <div>
                    <label for="profesor">Profesor:</label>
                    <select id="profesor" name="profesor" required>
                        $optionsProfesores
                    </select>
                </div>
                <div>
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">Acepto los Términos y Condiciones</label>
                </div>
                <div class="legenda">
                    <legend>2. Datos de Pago</legend>
                </div>
                <form action="procesarPago.php" method="POST">
                    <div>
                        <label for="metodo_pago">Método de Pago:</label>
                        <select id="metodo_pago" name="metodo_pago" required>
                            <option value="tarjeta">Tarjeta de Crédito</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div>
                        <label for="numero_tarjeta">Número de Tarjeta:</label>
                        <input id="numero_tarjeta" type="text" name="numero_tarjeta" required>
                    </div>
                    <div>
                        <label for="fecha_expiracion">Fecha de Expiración:</label>
                        <input id="fecha_expiracion" type="text" name="fecha_expiracion" placeholder="MM/AA" required>
                    </div>

                    <div class="boton">
                        <button type="submit">Confirmar Inscripción</button>
                    </div>
                </form>
            </fieldset>

        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        $nombre_usuario = trim($datos['nombre_usuario'] ?? '');
        $nombre_curso = $datos['nombre_curso'] ?? '';

        // Verificar existencia de curso y usuario
        $conn = Aplicacion::getInstance()->getConexionBd();
        $queryCurso = sprintf("SELECT * FROM Curso WHERE nombre_curso = '%s'", $conn->real_escape_string($nombre_curso));
        $queryUsuario = sprintf("SELECT * FROM Estudiante WHERE nombre_usuario = '%s'", $conn->real_escape_string($nombre_usuario));

        $resultadoCurso = $conn->query($queryCurso);
        $resultadoUsuario = $conn->query($queryUsuario);

        if (!$resultadoCurso || $resultadoCurso->num_rows === 0) {
            $this->errores['nombre_curso'] = "Curso no existente";
        }

        if (!$resultadoUsuario || $resultadoUsuario->num_rows === 0) {
            $this->errores['nombre_usuario'] = "Usuario no existente";
        }

        // Si no hay errores, crear la inscripción
        if (empty($this->errores)) {
            // Obtener curso y usuario
            $curso = $resultadoCurso->fetch_assoc();
            $usuario = $resultadoUsuario->fetch_assoc();

            // Crear el registro directamente en la base de datos
            $queryInsert = sprintf("INSERT INTO Registrado (u_id, curso_id, p_id) VALUES (%d, '%s', %d)",
                $usuario['id'],
                $conn->real_escape_string($nombre_curso),
                $datos['profesor']
            );

            if ($conn->query($queryInsert)) {
                echo "¡Inscripción exitosa!";
            } else {
                echo "Error al registrar la inscripción.";
            }
        }
    }

    private function obtenerProfesoresDisponibles() {
        // Obtener profesores de la tabla Profesor
        $conn = Aplicacion::getInstance()->getConexionBd();
        $queryProfesores = "SELECT * FROM Profesor";
        $resultadoProfesores = $conn->query($queryProfesores);

        $profesores = [];
        if ($resultadoProfesores && $resultadoProfesores->num_rows > 0) {
            while ($fila = $resultadoProfesores->fetch_assoc()) {
                $profesores[] = $fila;
            }
        }

        return $profesores;
    }
}
?>
