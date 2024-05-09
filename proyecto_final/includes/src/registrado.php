<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/Usuario.php'; // Asegúrate de incluir el archivo de la clase Usuario si aún no está incluido

class Registrado {

    private $u_id;
    private $curso_id;
    private $p_id; // Falta el punto y coma (;) al final de esta línea

    public function __construct($usuario, $curso, $profesor) {
        $this->u_id = $usuario;
        $this->curso_id = $curso;
        $this->p_id = $profesor;
    }

    public static function crea($usuario, $curso, $profesor) {
        $nombre_usuario = $usuario->getNombreUsuario(); // Utiliza la flecha ->
        $nombre_curso = $curso->getNombre(); // Utiliza la flecha ->
        $nombre_profesor = $profesor->getNombreUsuario(); // Utiliza la flecha ->
        $registrado = new Registrado($nombre_usuario, $nombre_curso, $nombre_profesor);
        return self::inserta($registrado);
    }

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
            $conn->real_escape_string($registrado->curso_id), // Falta una coma (,) aquí
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

    public function getUsuario() {
        return $this->u_id;
    }
    public function getCurso() {
        return $this->curso_id;
    }
    public function getProfesor() {
        return $this->p_id;
    }

    /**
     * Comprueba si un usuario está registrado en un curso
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
        return $result;
    }
}