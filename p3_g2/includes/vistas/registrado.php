<?php
namespace es\ucm\fdi\aw;
class Registrado {

    private $u_id;
    private $curso_id;

    public function __construct($usuario, $curso) {
        $this->u_id = $usuario;
        $this->curso_id = $curso;
    }

    public static function crea($usuario, $curso){
        $registrado = new Registrado($usuario.getNombreUsuario(), $curso.getNombre());
        return self::inserta($registrado);

    }

    private static function inserta($registrado){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $queryCheck = sprintf("SELECT COUNT(*) as count FROM Registrado WHERE u_id = '%s' AND curso_id = '%s'",
        $conn->real_escape_string($registrado->u_id),
        $conn->real_escape_string($registrado->curso_id)
    );

    $resCheck = $conn->query($queryCheck);
    $row = $resCheck->fetch_assoc();

    if ($row['count'] == 0) { // If the pair does not exist, proceed with insertion
        $query = sprintf("INSERT INTO Registrado(u_id, curso_id) VALUES ('%s', '%s')",
            $conn->real_escape_string($registrado->u_id),
            $conn->real_escape_string($regitrado->curso_id)
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
}