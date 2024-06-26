<?php
namespace es\ucm\fdi\aw;
abstract class Usuario {
    public const ADMIN_ROLE = 1;
    public const ESTUDIANTE_ROLE = 2;
    public const PROFESOR_ROLE = 3;
    public static function login($nombre_usuario, $contrasena) {
        $result = static::buscaUsuario($nombre_usuario);
        $usuario = $result;
        if ($usuario && $usuario->compruebaPassword($contrasena)) {
            return $usuario;
        }
        return false;
    }
    abstract public static function crea($nombre_usuario, $apellido, $email, $contrasena);
    protected static function creaUsuario($class, $nombre_usuario, $apellido, $email, $contrasena) {
        $user = new $class($nombre_usuario, $apellido, $email, self::hashPassword($contrasena));
        return $user->guarda();
    }
    public static function buscaUsuario($nombre_usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $tables = ['Estudiante', 'Profesor', 'Administrador'];
        $result = false;
        foreach ($tables as $table) {
            $query = sprintf("SELECT * FROM %s WHERE nombre_usuario='%s'",
                $table,
                $conn->real_escape_string($nombre_usuario)
            );
            $rs = $conn->query($query);
            
            if ($rs && $rs->num_rows > 0) {
                $fila = $rs->fetch_assoc();
                switch ($table) {
                    case 'Estudiante':
                        $result = new Estudiante($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                        break;
                    case 'Profesor':
                        $result = new Profesor($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                        break;
                    case 'Administrador':
                        $result = new Admin($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                        break;
                }
                $rs->free();
                break;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
            }
        }
        return $result;
    }
    
    private static function hashPassword($contrasena) {
        return password_hash($contrasena, PASSWORD_DEFAULT);
    }
    abstract public static function inserta($usuario);
    protected static function insertaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO %s(nombre_usuario, apellido, email, contrasena) VALUES ('%s', '%s', '%s', '%s')",
            $table,
            $conn->real_escape_string($usuario->nombre_usuario),
            $conn->real_escape_string($usuario->apellido),
            $conn->real_escape_string($usuario->email),
            $conn->real_escape_string($usuario->contrasena)
        );

        if ($conn->query($query)) {
            $usuario->id = $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }
    abstract protected static function actualiza($usuario);
    private static function actualizaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE %s U SET nombre_usuario = '%s', apellido = '%s', email='%s', password='%s' WHERE U.id=%d"
            , $table
            , $conn->real_escape_string($usuario->nombre_usuario)
            , $conn->real_escape_string($usuario->apellido)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $usuario->id
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }
    abstract public static function borra($usuario);
    public static function borraUsuario($table, $usuario) {
        if ($usuario->id !== null) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("DELETE FROM %s WHERE id = %d"
                , $table
                , $usuario->id
            );
            if ( ! $conn->query($query) ) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    private $id;
    private $nombre_usuario;
    private $apellido;
    private $email;
    private $contrasena;
    private function __construct($nombre_usuario, $apellido, $email, $contrasena, $id = null) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }
    public function compruebaPassword($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }
    public function guarda() {
        if ($this->id !== null) {
            return static::actualiza($this);
        }
        return static::inserta($this);
    }
    public function getId() {
        return $this->id;
    }
    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getEmail() {
        return $this->email;
    }
}