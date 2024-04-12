<?php

namespace es\ucm\fdi\aw;

class FormularioLogin extends Formulario {

    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => 'index.php']);
    }
    protected function generaCamposFormulario(&$datos) {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombre_usuario = $datos['nombre_usuario'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre_usuario', 'contrasena'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        <h1>Log In</h1>
        $htmlErroresGlobales
        <fieldset>
            <div class="legenda">
                Datos para reingresar
            </div>
            <div class="form-element">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input id="nombre_usuario" type="text" name="nombre_usuario" required value="$nombre_usuario">
                {$erroresCampos['nombre_usuario']}
            </div>
            <div class="form-element">
                <label for="contrasena">Contraseña:</label>
                <input id="contrasena" type="password" name="contrasena" required>
                {$erroresCampos['contrasena']}
            </div>
            <div class="boton">
                <button type="submit" name="login" value="login">Log In</button>
            </div>
        </fieldset>

        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombre_usuario = trim($datos['nombre_usuario'] ?? '');
        $nombre_usuario = filter_var($nombre_usuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre_usuario || empty($nombre_usuario) ) {
            $this->errores['nombre_usuario'] = 'El nombre de usuario no puede estar vacío';
        }

        $contrasena = trim($datos['contrasena'] ?? '');
        $contrasena = filter_var($contrasena, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $contrasena || empty($contrasena) ) {
            $this->errores['contrasena'] = 'La contrasena no puede estar vacío.';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::login($nombre_usuario,$contrasena);
            if (!$usuario) {
                $this->errores[] = "El usuario o el password no coinciden";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $nombre_usuario;
                $tipo = get_class($usuario);
                if ($tipo == 'es\ucm\fdi\aw\Admin') {
                    $_SESSION['tipo_usuario'] = Usuario::ADMIN_ROLE;
                } elseif ($tipo == 'es\ucm\fdi\aw\Estudiante') {
                    $_SESSION['tipo_usuario'] = Usuario::ESTUDIANTE_ROLE;
                } elseif ($tipo == 'es\ucm\fdi\aw\Profesor') {
                    $_SESSION['tipo_usuario'] = Usuario::PROFESOR_ROLE;
                }
            }
        }
    }
}