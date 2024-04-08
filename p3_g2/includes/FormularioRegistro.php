<?php

namespace es\ucm\fdi\aw;

class FormularioRegistro extends Formulario {

    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }
    protected function generaCamposFormulario(&$datos) {
        $nombre_usuario = $datos['nombre_usuario'] ?? '';
        $apellido = $datos['apellido'] ?? '';
        $email = $datos['email'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre_usuario', 'apellido', 'email', 'password', 'password2'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <main>
            <article>
                <h1>Registro de usuario</h1>
                <fieldset>
                    <div class="legenda">
                        <legend>Datos para el registro</legend>
                    </div>
                    <div>
                        <label for="nombre_usuario">Nombre de usuario:</label>
                        <input id="nombre_usuario" type="text" name="nombre_usuario" />
                        {$erroresCampos['nombre_usuario']}
                    </div>
                    <div>
                        <label for="apellido">Apellido:</label>
                        <input id="apellido" type="text" name="apellido" />
                        {$erroresCampos['apellido']}
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input id="email" type="email" name="email" />
                        {$erroresCampos['email']}
                    </div>
                    <div>
                        <label for="password">Password:</label>
                        <input id="password" type="password" name="password" />
                        {$erroresCampos['password']}
                    </div>
                    <div>
                        <label for="password2">Reintroduce el password:</label>
                        <input id="password2" type="password" name="password2" />
                        {$erroresCampos['password2']}
                    </div>
                    <div class="boton">
                        <button type="submit" name="registro">Registrar</button>
                    </div>
                </fieldset>
            </article>
        </main>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombre_usuario = trim($datos['nombre_usuario'] ?? '');
        $nombre_usuario = filter_var($nombre_usuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre_usuario || empty($nombre_usuario) || mb_strlen($nombre_usuario) < 3) {
            $this->errores['nombre_usuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
        }

        $apellido = trim($datos['apellido'] ?? '');
        $apellido = filter_var($apellido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $apellido || empty($apellido) || mb_strlen($apellido) < 3) {
            $this->errores['apellido'] = 'El apellido de usuario tiene que tener una longitud de al menos 3 caracteres.';
        }

        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ( ! $email || empty($email)) {
            $this->errores['email'] = 'Introduce una dirección de correo electrónico válida.';
        }
        $domain = substr($email, strpos($email, '@') + 1);
        // Verifica si el dominio del correo electrónico indica que es un administrador
        if (strpos($domain, 'admin') !== false) {
            $table = 'Administrador';
            $role = ADMIN_ROLE;
        } elseif (strpos($domain, 'estudiante') !== false) {
            $table = 'Estudiante';
            $role = ESTUDIANTE_ROLE;
        } elseif (strpos($domain, 'profesor') !== false) {
            $table = 'Profesor';
            $role = PROFESOR_ROLE;
        } else {
            $erroresFormulario['email'] = 'Introduce una dirección de correo electrónico válida.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || mb_strlen($password) < 5 ) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password2 = trim($datos['password2'] ?? '');
        $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password2 || $password != $password2 ) {
            $this->errores['password2'] = 'Los passwords deben coincidir';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::buscaUsuario($nombre_usuario);
            if ($usuario) {
                $this->errores[] = "El usuario ya existe";
            } else {
                $usuario = Usuario::crea($nombre_usuario, $apellido, $email, $password, $role);
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $nombre_usuario;
                $_SESSION['tipo_usuario'] = $role;
            }
        }
    }
}