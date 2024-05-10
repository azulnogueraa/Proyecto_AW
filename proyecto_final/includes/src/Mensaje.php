<?php
namespace es\ucm\fdi\aw;
class Mensaje {

    private $id;
    private $mensaje;
    private $created_at;
    private $user_id;
    private $tipo_usuario;
    private $nombre_curso;

    /**
     * Constructor de la clase Mensaje
     */
    public function __construct($mensaje, $created_at, $user_id, $tipo_usuario, $nombre_curso, $id = null) {
        $this->id = $id;
        $this->mensaje = $mensaje;
        $this->created_at = $created_at;
        $this->user_id = $user_id;
        $this->tipo_usuario = $tipo_usuario;
        $this->nombre_curso = $nombre_curso;
    }

    /**
     * Crea un nuevo mensaje
     */
    public function crea($mensaje, $created_at, $user_id, $tipo_usuario, $nombre_curso) {
        $mess = new Mensaje($mensaje, $created_at, $user_id, $tipo_usuario, $nombre_curso);
        return $mess->guarda();
    }

    /**
     * Guarda un mensaje en la base de datos
     */
    public function guarda() {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    /**
     * Actualiza un mensaje en la base de datos
     * @return true si se ha actualizado correctamente, false en caso contrario
     */
    private static function actualiza($mess) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE Mensaje SET mensaje = '%s', created_at = '%s', user_id = '%s', tipo_usuario = '%s', nombre_curso = '%s' WHERE id = '%s'"
            , $conn->real_escape_string($mess->mensaje)
            , $conn->real_escape_string($mess->created_at)
            , $conn->real_escape_string($mess->user_id)
            , $conn->real_escape_string($mess->tipo_usuario)
            , $conn->real_escape_string($mess->nombre_curso)
            , $mess->id
        );
        if ($conn->query($query)) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Inserta un mensaje en la base de datos
     * @return true si se ha insertado correctamente, false en caso contrario
     */
    private static function inserta($mess) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO Mensaje(mensaje, created_at, user_id, tipo_usuario, nombre_curso) VALUES ('%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($mess->mensaje)
            , $conn->real_escape_string($mess->created_at)
            , $conn->real_escape_string($mess->user_id)
            , $conn->real_escape_string($mess->tipo_usuario)
            , $conn->real_escape_string($mess->nombre_curso)
        );
        if ($conn->query($query)) {
            $mess->id = $conn->insert_id;
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Borra los mensajes de un usuario
     * @param int $user_id ID del usuario
     * @param string $tipo_usuario Tipo de usuario
     * @return true si se han borrado correctamente, false en caso contrario
     */
    public static function borraPorUsuario($user_id, $tipo_usuario) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM Mensaje WHERE user_id = '%i' AND tipo_usuario = '%s'"
            , $user_id
            , $conn->real_escape_string($tipo_usuario)
        );
        if ($conn->query($query)) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Borra los mensajes de un curso
     * @param string $nombre_curso Nombre del curso
     * @return true si se han borrado correctamente, false en caso contrario
     */
    public static function borraPorCurso($nombre_curso) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM Mensaje WHERE nombre_curso = '%s'"
            , $conn->real_escape_string($nombre_curso)
        );
        if ($conn->query($query)) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
}