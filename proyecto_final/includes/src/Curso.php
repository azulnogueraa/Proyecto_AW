<?php
namespace es\ucm\fdi\aw;
use mysqli_sql_exception;
class Curso {

    private $precio;
    private $nombre_curso;
    private $descripcion;
    private $duracion;
    private $categoria;
    private $nivel_dificultad;
    private $estudiante; // Agregamos la propiedad para el estudiante asignado

    public function __construct($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad, $estudiante = null) {
        $this->nombre_curso = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->duracion = $duracion;
        $this->categoria = $categoria;
        $this->nivel_dificultad = $nivel_dificultad;
        $this->estudiante = $estudiante;
    }

    public static function crea($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad){
        $curso = new Curso($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad);
        return self::inserta($curso);

    }

    private static function inserta($curso){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO Curso(nombre_curso, precio, descripcion, duracion, categoria, nivel_dificutad) VALUES ('%s', '%s', '%s')"
            , $conn->real_escape_string($curso->nombre_curso)                  
            , $conn->real_escape_string($curso->precio)
            , $conn->real_escape_string($curso->descripcion)
            , $conn->real_escape_string($curso->duracion)
            , $conn->real_escape_string($curso->categoria)
            , $conn->real_escape_string($curso->nivel_dificultad)
        );
        $res = $conn->query($query);//ponerlo en un if para seguridad y exponer un mensaje
        return $result !== false;
    }

    public static function crearCurso($nombre, $descripcion, $profesorId, $duracion, $nivelDificultad, $categoria, $precio) {
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "INSERT INTO Curso (nombre_curso, descripcion, profesor_id, duracion, nivel_dificultad, categoria, precio, fecha_creacion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp())";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissss", $nombre, $descripcion, $profesorId, $duracion, $nivelDificultad, $categoria, $precio);

        $result = $stmt->execute();
    
        if ($result === false) {
            error_log("Error al insertar curso: " . $stmt->error);
            return false;
        }
    
        $stmt->close();
    
        return true;
    }

    public static function buscaCursoPorNombre($nombreCurso) {
        // Obtener la conexión a la base de datos desde la aplicación
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para buscar un curso por su nombre
        $query = "SELECT * FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $curso = new Curso($row['nombre_curso'], $row['descripcion'], $row['duracion'], $row['nivel_dificultad'], $row['categoria'], $row['precio']);
            return $curso;
        }

        return null; // Si no se encuentra ningún curso con ese nombre
    }

    public static function buscaCursoPorId($idCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        // Consulta SQL para buscar un curso por su ID
        $query = "SELECT * FROM Curso WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idCurso);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $curso = new Curso($row['nombre_curso'], $row['precio'], $row['descripcion'], $row['duracion'], $row['categoria'], $row['nivel_dificultad']);
            return $curso;
        }
    
        return null; // Si no se encuentra ningún curso con ese ID
    }
    

    public function getNombre() {
        return $this->nombre_curso;
    }
    public function getPrecio() {
        return $this->precio;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    public function getNivelDificultad() {
        return $this->nivel_dificultad;
    }    

    public function getEstudiante() {
        return $this->estudiante;
    }
    
    static public function obtenerCursos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Curso");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $curso = new Curso(
                    $row['nombre_curso'],
                    $row['precio'],
                    $row['descripcion'],
                    $row['duracion'],
                    $row['categoria'],
                    $row['nivel_dificultad']);
                $result[] = $curso;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    static public function obtenerNombreCursos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("SELECT nombre_curso FROM Curso");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $result[] = $row["nombre_curso"];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function editarCurso($nombreCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Consulta para obtener la información del curso por su nombre
        $query = "SELECT * FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el curso
        if ($result->num_rows > 0) {
            // Obtener los datos del curso como un array asociativo
            $curso = $result->fetch_assoc();
            return $curso; // Devolver los datos del curso
        } else {
            // Curso no encontrado, lanzar una excepción o devolver null
            return null;
        }
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    public function setNivelDificultad($nivel_dificultad) {
        $this->nivel_dificultad = $nivel_dificultad;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function actualizarCurso() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "UPDATE Curso SET descripcion=?, duracion=?, nivel_dificultad=?, categoria=?, precio=? WHERE nombre_curso=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssds", $this->descripcion, $this->duracion, $this->nivel_dificultad, $this->categoria, $this->precio, $this->nombre_curso);

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function borrarCurso($nombreCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Consulta SQL para eliminar el curso por su nombre
        $query = "DELETE FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);

        // Ejecutar la consulta y verificar el resultado
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    
    public function tieneAlumnosInscritos() {
        // Obtener la conexión a la base de datos desde la aplicación
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para contar los registros de alumnos inscritos en este curso
        $query = "SELECT COUNT(*) as total FROM Registrado WHERE curso_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $this->nombre_curso);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $total_alumnos_inscritos = $row['total'];
            return $total_alumnos_inscritos > 0; // Devuelve true si hay alumnos inscritos, false si no
        }

        return false; 
    }

}
