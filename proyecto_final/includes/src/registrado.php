<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/Usuario.php'; // Asegúrate de incluir el archivo de la clase Usuario si aún no está incluido

class Registrado {

    private $u_id;
    private $nombre_curso;

    public function __construct($usuario, $curso) {
        $this->u_id = $usuario;
        $this->nombre_curso = $curso;
    }

    public static function crea($usuario, $curso){
        $id_usuario = $usuario->getId(); // Utiliza la flecha ->
        $nombre_curso = $curso->getNombre(); // Utiliza la flecha ->
        $registrado = new Registrado($id_usuario, $nombre_curso);
        return self::inserta($registrado);
    }

    private static function inserta($registrado){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $queryCheck = sprintf("SELECT COUNT(*) as count FROM Registrado WHERE u_id = '%d' AND curso_id = '%s'",
                                $conn->real_escape_string($registrado->u_id),
                                $conn->real_escape_string($registrado->nombre_curso)
                                );

        $resCheck = $conn->query($queryCheck);
        $row = $resCheck->fetch_assoc();

        if ($row['count'] == 0) { // If the pair does not exist, proceed with insertion
            $query = sprintf("INSERT INTO Registrado(u_id, curso_id) VALUES ('%d', '%s')",
                $conn->real_escape_string($registrado->u_id),
                $conn->real_escape_string($registrado->nombre_curso)
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
        return $this->nombre_curso;
    }
}
?>
