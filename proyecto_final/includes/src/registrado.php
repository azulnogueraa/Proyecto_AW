<?php
namespace es\ucm\fdi\aw;

class Registrado {

    private $u_id;
    private $curso_id;
    private $p_id;

    /**
     * Constructor de la clase Registrado
     */
    public function __construct($usuario, $curso, $profesor) {
        $this->u_id = $usuario;
        $this->curso_id = $curso;
        $this->p_id = $profesor;
    }

    /**
     * Crea un nuevo registro
     */
    public static function crea($usuario, $curso, $profesor) {
        $nombre_usuario = $usuario->getNombreUsuario();
        $nombre_curso = $curso->getNombre();
        $nombre_profesor = $profesor->getNombreUsuario();
        $registrado = new Registrado($nombre_usuario, $nombre_curso, $nombre_profesor);
        return self::inserta($registrado);
    }

    /**
     * Inserta un nuevo registro en la base de datos
     * @return boolean true si se ha insertado correctamente, false en caso contrario
     */
    private static function inserta($registrado){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $queryCheck = sprintf("SELECT COUNT(*) as count FROM Registrado WHERE u_id = '%s' AND curso_id = '%s' AND p_id = '%s'",
            $conn->real_escape_string($registrado->u_id),
            $conn->real_escape_string($registrado->curso_id),
            $conn->real_escape_string($registrado->p_id)
        );

        $resCheck = $conn->query($queryCheck);
        $row = $resCheck->fetch_assoc();

        if ($row['count'] == 0) { // If the pair does not exist, proceed with insertion
            $query = sprintf("INSERT INTO Registrado(u_id, curso_id, p_id) VALUES ('%s', '%s', '%s')",
                $conn->real_escape_string($registrado->u_id),
                $conn->real_escape_string($registrado->curso_id),
                $conn->real_escape_string($registrado->p_id)
            );

            $res = $conn->query($query);
            $result = $res !== false;
        } else {
            // Pair already exists in the database
            echo "Ya estas registrado para este curso";
        }
        return $result;
    }

    /**
     * Devuelve el id del usuario
     */
    public function getUsuarioId() {
        return $this->u_id;
    }

    /**
     * Devuelve el id del curso
     */
    public function getCursoId() {
        return $this->curso_id;
    }

    /**
     * Devuelve el id del profesor
     */
    public function getProfesorId() {
        return $this->p_id;
    }

    /**
     * Comprueba si un usuario está registrado en un curso
     * @return boolean true si el usuario está registrado, false en caso contrario
     */
    public static function esRegistrado($u_id, $curso_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT COUNT(*) as count FROM Registrado WHERE u_id = '%s' AND curso_id = '%s'",
            $conn->real_escape_string($u_id),
            $conn->real_escape_string($curso_id)
        );
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $count = $rs->fetch_assoc()['count'];
            $result = $count > 0;
        }
        $rs->free();
        return $result;
    }

    /**
     * Da de baja a un usuario de un curso
     * @return boolean true si se ha dado de baja correctamente, false en caso contrario
     */
    public static function darseDeBaja($u_id, $curso_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM Registrado WHERE u_id = '%s' AND curso_id = '%s'",
            $conn->real_escape_string($u_id),
            $conn->real_escape_string($curso_id)
        );
        $result = false;
        if ($conn->query($query)) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Devuelve los ids de los estudiantes registrados en un curso
     */
    public static function getEstudianteIdRegistrados($curso_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT u_id FROM Registrado WHERE curso_id = '%s'",
            $conn->real_escape_string($curso_id)
        );
        $rs = $conn->query($query);
        $result = [];
        if ($rs) {
            while($row = $rs->fetch_assoc()) {
                $result[] = $row['u_id'];
            }
        }
        $rs->free();
        return $result;
    }
}