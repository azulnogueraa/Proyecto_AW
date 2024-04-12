<?php
namespace es\ucm\fdi\aw;

class FormularioInscripcion extends Formulario {

    public function __construct() {
        parent::__construct('formInscripcion', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        $nombre_curso = $datos['nombre_curso'] ?? '';
        $nombre_usuario = $datos['nombre_usuario'] ?? '';
        $apellido = $datos['apellido'] ?? '';
        $email = $datos['email'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            <h1>Formulario de Inscripción</h1>
            $htmlErroresGlobales
            <div class="formulario-seccion" id="datos-seccion">
                <form action="procesarInscripcion.php" method="POST">
                    <input type="hidden" name="nombre_curso" value="{$nombre_curso}">
                        
                    <fieldset>
                        <div class="legenda">
                            <legend>1. Datos para la inscripción</legend>
                        </div>
                        <div>
                            <label for="nombre_usuario">Nombre de usuario:</label>
                            <input id="nombre_usuario" type="text" name="nombre_usuario" required value="{$nombre_usuario}"/>
                        </div>
                        <div>
                            <label for="apellido">Apellido:</label>
                            <input id="apellido" type="text" name="apellido" required value="{$apellido}"/>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input id="email" type="email" name="email" required value="{$email}" />
                        </div>
                        <div>
                            <input type="checkbox" id="terminos" name="terminos" required>
                            <label for="terminos">Acepto los Términos y Condiciones</label>
                        </div>

                        <div class="boton">
                            <button type="button" onclick="mostrarSeccion('pago-seccion')">Siguiente</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="formulario-seccion" id="pago-seccion" style="display: none;">
                <fieldset>
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
            </div>
            <script>
                let pasoActual = 1;
                const totalPasos = 2;

                function mostrarSeccion(seccion) {
                    document.querySelectorAll('.formulario-seccion').forEach(function(seccion) {
                        seccion.style.display = 'none';
                    });
                    let seccionElement = document.getElementById(seccion);
                    if (seccionElement) {
                        seccionElement.style.display = 'block';

                        // Actualizar indicador de paso
                        let indicadorPaso = document.getElementById('indicador-paso');
                        if (indicadorPaso) {
                            indicadorPaso.innerText = `Paso pasoActual de totalPasos`;
                        }
                    }
                }
            </script>
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
        $queryUsuario = sprintf("SELECT * FROM Usuario WHERE nombre_usuario = '%s'", $conn->real_escape_string($nombre_usuario));

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

            // Crear registro
            $registrado = Registrado::crea($usuario, $curso);
        }
    }
}
?>
