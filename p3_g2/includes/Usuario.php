<?php

namespace es\ucm\fdi\aw;

class Usuario {

    public const ADMIN_ROLE = 1;
    public const ESTUDIANTE_ROLE = 2;
    public const PROFESOR_ROLE = 3;

    public static function login($nombre_usuario, $contrasena) {
        $result = self::buscaUsuario($nombre_usuario);
        $usuario = $result->value1;
        $rol = $result->value2;
        if ($usuario && $usuario->compruebaPassword($contrasena)) {
            return self::cargaRoles($usuario);
        }
        return false;
    }

    public static function crea($nombre_usuario, $apellido, $email, $contrasena, $rol) {
        $user = new Usuario($nombre_usuario, $apellido, $email, self::hashPassword($contrasena));
        $user->añadeRol($rol);
        return $user->guarda();
    }

    public static function buscaUsuario($nombre_usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $rol = null;
        $query = sprintf("SELECT * FROM Estudiante E WHERE E.nombre_usuario='%s'", $conn->real_escape_string($nombre_usuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                $rol = ESTUDIANTE_ROLE;
            }
            $rs->free();
        } else { // El usuario no es un estudiante, verificamos si es un profesor
            $query = sprintf("SELECT * FROM Profesor P WHERE P.nombre_usuario='%s'", $conn->real_escape_string($nombre_usuario));
            $rs = $conn->query($query);
            if ($rs) {
                $fila = $rs->fetch_assoc();
                if ($fila) {
                    $result = new Usuario($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                    $rol = PROFESOR_ROLE;
                }
                $rs->free();
            } else { // El usuario no es un profesor, verificamos si es un admin
                $query = sprintf("SELECT * FROM Administrador A WHERE A.nombre_usuario='%s'", $conn->real_escape_string($nombre_usuario));
                $rs = $conn->query($query);
                if ($rs) {
                    $fila = $rs->fetch_assoc();
                    if ($fila) {
                        $result = new Usuario($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],  $fila['id']);
                        $rol = ADMIN_ROLE;
                    }
                    $rs->free();
                } else {
                    error_log("Error BD ({$conn->errno}): {$conn->error}");
                }
            }
        }
        $array = new \TwoValues();
        return array($result, $rol);
    }

    private static function hashPassword($contrasena) {
        return password_hash($contrasena, PASSWORD_DEFAULT);
    }

    private static function cargaRoles($usuario)
    {
        $roles=[];
            
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT RU.rol FROM RolesUsuario RU WHERE RU.usuario=%d"
            , $usuario->id
        );
        $rs = $conn->query($query);
        if ($rs) {
            $roles = $rs->fetch_all(MYSQLI_ASSOC);
            $rs->free();

            $usuario->roles = [];
            foreach($roles as $rol) {
                $usuario->roles[] = $rol['rol'];
            }
            return $usuario;

        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return false;
    }

    private static function inserta($usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $table = '';
        switch ($usuario->rol) {
            case ADMIN_ROLE:
                $table = 'Administrador';
                break;
            case ESTUDIANTE_ROLE:
                $table = 'Estudiante';
                break;
            case PROFESOR_ROLE:
                $table = 'Profesor';
                break;
            default:
                return false;
        }
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

    private static function actualiza($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $table = '';
        switch ($usuario->rol) {
            case ADMIN_ROLE:
                $table = 'Administrador';
                break;
            case ESTUDIANTE_ROLE:
                $table = 'Estudiante';
                break;
            case PROFESOR_ROLE:
                $table = 'Profesor';
                break;
            default:
                return false;
        }
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

    public function borra($usuario)
    {
        if ($usuario->id !== null && $usuario->rol !== null) {
            $table = '';
            switch ($usuario->rol) {
                case ADMIN_ROLE:
                    $table = 'Administrador';
                    break;
                case ESTUDIANTE_ROLE:
                    $table = 'Estudiante';
                    break;
                case PROFESOR_ROLE:
                    $table = 'Profesor';
                    break;
                default:
                    return false;
            }
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("DELETE FROM %s U WHERE U.id = %d"
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
    private $rol;

    private function __construct($nombre_usuario, $apellido, $email, $contrasena, $id = null, $rol = null) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }
    public function añadeRol($role)
    {
        $this->rol = $role;
    }
    public function getRol() {
        return $this->rol;
    }
    public function compruebaPassword($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }

    public function guarda() {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }
}