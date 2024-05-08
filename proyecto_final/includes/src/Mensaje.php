<?php
namespace es\ucm\fdi\aw;
use mysqli_sql_exception;
class Mensaje {

    private $id;
    private $curso_nombre;
    private $contenido;
    private $creacion;
    private $u_id;
   

    public function __construct($nombre, $u_id, $contenido) {
        $this->curso_nombre = $nombre;
        $this->u_id = $u_id;
        $this->contenido = $contenido;
        //$this->creacion = $creacion;
    }

    public static function crea($nombre, $u_id, $contenido){
        $msg = new Mensaje($nombre, $u_id, $contenido);
        return self::inserta($msg);

    }

    private static function inserta($mensaje){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO Mensaje(curso_nombre, u_id, contenido, created_at) VALUES ('%s', '%s', '%s', current_timestamp())"
            , $conn->real_escape_string($mensaje->curso_nombre)                  
            , $conn->real_escape_string($mensaje->u_id)
            , $conn->real_escape_string($mensaje->contenido)
        );
        
        if ($conn->query($query)) {
            $mensaje->id = $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $mensaje;
    }

    // public static function crearMensaje($nombre, $u_id, $contenido) {
    //     $conn = Aplicacion::getInstance()->getConexionBd();
    
    //     $query = "INSERT INTO Mensaje (curso_nombre, contenido, u_id, creacion) 
    //               VALUES (?, ?, ?, current_timestamp())";
    
    //     $stmt = $conn->prepare($query);
    //     $stmt->bind_param("ssissss", $nombre, $u_id, $contenido);

    //     $result = $stmt->execute();
    
    //     if ($result === false) {
    //         error_log("Error al insertar mensaje: " . $stmt->error);
    //         return false;
    //     }
    
    //     $stmt->close();
    
    //     return true;
    // }
    

    public function getCurso_nombre() {
        return $this->curso_nombre;
    }
    public function getU_id() {
        return $this->u_id;
    }
    public function getContenido() {
        return $this->contenido;
    }

    public function getCreacion() {
        return $this->creacion;
    }

    public function getId() {
        return $this->id;
    }

    
    static public function obtenerMensajes($id_curso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Mensaje Where nombre_curso = '%s'", $conn->real_escape_string($id_curso));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $msg = new Mensaje(
                    $row['nombre_curso'],
                    $row['u_id'],
                    $row['contenido'],);
                $result[] = $msg;
            }
            $rs->free();
        } else {

            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function getSender($u_id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Estudiante WHERE id = %d", $u_id);
        if($res = $conn->query($query)){
            return $res;
        }else{
            $query2 = sprintf("SELECT * FROM Profesor WHERE id = %d", $u_id);
            if($res2 = $conn->query($query2)){
                return $res2;
            }
        }

    }

    // static public function obtenerNombreCursos() {
    //     $conn = Aplicacion::getInstance()->getConexionBd();
    //     $query=sprintf("SELECT nombre_curso FROM Curso");
    //     $rs = $conn->query($query);
    //     $result = false;
    //     if ($rs) {
    //         $result = [];
    //         while ($row = $rs->fetch_assoc()) {
    //             $result[] = $row["nombre_curso"];
    //         }
    //         $rs->free();
    //     } else {
    //         error_log("Error BD ({$conn->errno}): {$conn->error}");
    //     }
    //     return $result;
    // }

    

    // public static function borrarCurso($nombreCurso) {
    //     $conn = Aplicacion::getInstance()->getConexionBd();

    //     // Consulta SQL para eliminar el curso por su nombre
    //     $query = "DELETE FROM Curso WHERE nombre_curso = ?";
    //     $stmt = $conn->prepare($query);
    //     $stmt->bind_param("s", $nombreCurso);

    //     // Ejecutar la consulta y verificar el resultado
    //     $result = $stmt->execute();
    //     $stmt->close();

    //     return $result;
    // }

}
