<?php
namespace es\ucm\fdi\aw;
// I don't use the js file but declre a fct directly throught <script> 
class FormularioRegistro extends Formulario {

    public function __construct() {
        parent::__construct('formInscripcion', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos){
        //we shouldn't need to ask the data again and take it directly from the session
        $nombre_curso = $datos['nombre_curso'];
        $nombre_usuario = $datos['nombre_usuario'] ?? '';
        $apellido = $datos['apellido'] ?? '';
        $email = $datos['email'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre_usuario', 'apellido', 'email'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF    
        <h1>Formulario de Inscripción</h1>
        EOF;
        // $htmlErroresGlobales
        // <div class="formulario-seccion" id="datos-seccion">
        //     <form action="procesarInscripcion.php" method="POST">
                    
        //         <input type="hidden" name="nombre_curso" value="$nombre_curso">
                
        //         <fieldset>
        //             <div class="legenda">
        //                 <legend>1. Datos para la inscripcion</legend>
        //             </div>
        //             <div>
        //                 <label for="nombre_usuario">Nombre de usuario:</label>
        //                 <input id="nombre_usuario" type="text" name="nombre_usuario" required value="$nombre_usuario"/>
        //             </div>
        //             <div>
        //                 <label for="apellido">Apellido:</label>
        //                 <input id="apellido" type="text" name="apellido" required value="$apellido"/>
        //             </div>
        //             <div>
        //                 <label for="email">Email:</label>
        //                 <input id="email" type="email" name="email" required value="$email" />
        //             </div>
        //             <div>
        //                 <input type="checkbox" id="terminos" name="terminos" required>
        //                 <label for="terminos">Acepto los Términos y Condiciones</label>
        //             </div>

        //             <div class="boton">
        //                 <button type="button" onclick="mostrarSeccion('pago-seccion')">Siguiente</button>
        //             </div>
        //         </fieldset>
        //     </form>
        // </div>

        // <div class="formulario-seccion" id="pago-seccion" style="display: none;">
        //     <fieldset>
        //         <div class="legenda">
        //                 <legend>2. Datos de Pago</legend>
        //         </div>
        //         <form action="procesarPago.php" method="POST">
        //             <div>
        //                 <label for="metodo_pago">Método de Pago:</label>
        //                 <select id="metodo_pago" name="metodo_pago" required>
        //                     <option value="tarjeta">Tarjeta de Crédito</option>
        //                     <option value="paypal">PayPal</option>
        //                 </select>
        //             </div>
        //             <div>
        //                 <label for="numero_tarjeta">Número de Tarjeta:</label>
        //                 <input id="numero_tarjeta" type="text" name="numero_tarjeta" required>
        //             </div>
        //             <div>
        //                 <label for="fecha_expiracion">Fecha de Expiración:</label>
        //                 <input id="fecha_expiracion" type="text" name="fecha_expiracion" placeholder="MM/AA" required>
        //             </div>

        //             <div class="boton">
        //                 <button type="submit">Confirmar Inscripción</button>
        //             </div>
        //         </form>
        //     </fieldset>
        // </div>
        // <script>
        //     let pasoActual = 1;
        //     const totalPasos = 2;

        //     function mostrarSeccion(seccion) {
        //         document.querySelectorAll('.formulario-seccion').forEach(function(seccion) {
        //             seccion.style.display = 'none';
        //         });
        //         document.getElementById(seccion).style.display = 'block';

        //         // Actualizar indicador de paso
        //         document.getElementById('indicador-paso').innerText = `Paso ${pasoActual} de ${totalPasos}`;
        //     }
        // </script>
        // EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        $nombre_usuario = trim($datos['nombre_usuario'] ?? '');
        $nombre_curso = $datos['nombre_curso'];

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * From Curso c Where c.nombre_curso == $nombre_curso");
        $queryUser = sprintf("SELECT * From Usuario u Where u.nombre_usuario == $nombre_usuario");
        $curso = $conn->query($query);
        $usuario = $conn->query($queryUser);
        if( !$usuario){
            $this->errores['nombre_curso'] = "curso no existante";
        }
        if (!$usuario){
            $this->errores['nombre_usuario'] = "usuario no existante";
        }

        if (count($this->errores) === 0) {
            $registrado = Registrado::crea($usuario, $curso);
        }
    }
}