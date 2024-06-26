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

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            <h1>Formulario de Inscripción</h1>
            $htmlErroresGlobales
            <fieldset>
                <div class="legenda">
                    1. Datos para la inscripción
                </div>
                <div>
                    <label for="nombre_usuario">Nombre de usuario:</label>
                    <input id="nombre_usuario" type="text" name="nombre_usuario" required value="{$nombre_usuario}" readonly>
                </div>
                <div>
                    <label for="nombre_curso">Nombre del curso:</label>
                    <input id="nombre_curso" type="text" name="nombre_curso" value="$nombre_curso" readonly>
                </div>
                <div>
                    <label for="apellido">Apellido:</label>
                    <input id="apellido" type="text" name="apellido" required value="{$apellido}" readonly>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" required value="{$email}" readonly>
                </div>
                <div>
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">Acepto los Términos y Condiciones</label>
                </div>
                <div class="legenda">
                    2. Datos de Pago
                </div>
                <div>
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="" disabled selected>Seleccione un método de pago</option>
                        <option value="tarjeta">Tarjeta de Crédito</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>
                <div>
                    <label for="numero_tarjeta">Número de Tarjeta:</label>
                    <input id="numero_tarjeta" type="number" name="numero_tarjeta" required>
                </div>
                <div>
                    <label for="fecha_expiracion">Fecha de Expiración:</label>
                    <input id="fecha_expiracion" type="date" name="fecha_expiracion" required>
                </div>

                <div class="boton">
                    <button type="submit">Confirmar Inscripción</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        $nombre_usuario = trim($datos['nombre_usuario'] ?? '');
        $nombre_curso = $datos['nombre_curso'] ?? '';

        // Verificar existencia de curso y usuario
        $usuario = Usuario::buscaUsuario($nombre_usuario);
        if (!$usuario) {
            $this->errores['nombre_usuario'] = "Usuario no existente";
        }
        $curso = Curso::buscaCursoPorNombre($nombre_curso);
        if (!$curso) {
            $this->errores['nombre_curso'] = "Curso no existente";
        }

        // Si no hay errores, crear la inscripción
        if (empty($this->errores)) {
            $registrado = Registrado::crea($usuario, $curso);
            if ($registrado) {
                return 'index.php';
            } else {
                $this->errores['global'] = "Error al registrar la inscripción.";
            }
        }
    }
}
