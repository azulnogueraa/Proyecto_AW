<?php
namespace es\ucm\fdi\aw;

class Admin extends Usuario {

    // Crear un nuevo Administrador
    public static function crea($nombre_usuario, $apellido, $email, $contrasena) {
        return self::creaUsuario(__CLASS__, $nombre_usuario, $apellido, $email, $contrasena);
    }

    // Insertar un nuevo Administrador en la base de datos
    public static function inserta($usuario) {
        $table = 'Administrador';
        return self::insertaUsuario($table, $usuario);
    }

    // Actualizar un Administrador en la base de datos
    protected static function actualiza($usuario) {
        $table = 'Administrador';
        return self::actualizaUsuario($table, $usuario);
    }

    // Obtener todos los usuarios
    public static function obtenerUsuarios() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT nombre_usuario FROM Estudiante
                  UNION
                  SELECT nombre_usuario FROM Profesor
                  UNION
                  SELECT nombre_usuario FROM Administrador";
        $rs = $conn->query($query);

        $result = false;
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $result[] = $row["nombre_usuario"];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $result;
    }

    // Borrar un usuario de una tabla específica
    public function borrarUsuario($table, $usuario) {
        return Usuario::borraUsuario($table, $usuario);
    }
}
