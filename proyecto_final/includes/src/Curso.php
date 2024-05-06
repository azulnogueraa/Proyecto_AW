<?php
namespace es\ucm\fdi\aw;
use mysqli_sql_exception;

class Curso {
    private $nombre_curso;
    private $precio;
    private $descripcion;
    private $duracion;
    private $categoria;
    private $nivel_dificultad;

    public function __construct($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad) {
        $this->nombre_curso = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->duracion = $duracion;
        $this->categoria = $categoria;
        $this->nivel_dificultad = $nivel_dificultad;
    }

    /**
     * Crea un nuevo curso y lo inserta en la base de datos.
     */
    public static function crea($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad) {
        $curso = new Curso($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad);
        return self::inserta($curso);
    }

    /**
     * Inserta un curso en la base de datos.
     */
    private static function inserta($curso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO Curso (nombre_curso, precio, descripcion, duracion, categoria, nivel_dificultad) 
            VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
            $conn->real_escape_string($curso->nombre_curso),
            $conn->real_escape_string($curso->precio),
            $conn->real_escape_string($curso->descripcion),
            $conn->real_escape_string($curso->duracion),
            $conn->real_escape_string($curso->categoria),
            $conn->real_escape_string($curso->nivel_dificultad)
        );
        $result = $conn->query($query);
        return $result !== false;
    }

    /**
     * Crea un nuevo curso asignándole un profesor y una fecha de creación.
     */
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

    /**
     * Busca un curso en la base de datos por su nombre.
     */
    public static function buscaCursoPorNombre($nombreCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Curso(
                $row['nombre_curso'],
                $row['precio'],
                $row['descripcion'],
                $row['duracion'],
                $row['categoria'],
                $row['nivel_dificultad']
            );
        }
        return null;
    }

    /**
     * Obtiene el nombre del curso.
     */
    public function getNombre() {
        return $this->nombre_curso;
    }

    /**
     * Obtiene el precio del curso.
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Obtiene la descripción del curso.
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Obtiene la categoría del curso.
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Obtiene la duración del curso.
     */
    public function getDuracion() {
        return $this->duracion;
    }

    /**
     * Obtiene el nivel de dificultad del curso.
     */
    public function getNivelDificultad() {
        return $this->nivel_dificultad;
    }

    /**
     * Obtiene todos los cursos disponibles.
     */
    static public function obtenerCursos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM Curso";
        $rs = $conn->query($query);
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $result[] = new Curso(
                    $row['nombre_curso'],
                    $row['precio'],
                    $row['descripcion'],
                    $row['duracion'],
                    $row['categoria'],
                    $row['nivel_dificultad']
                );
            }
            $rs->free();
            return $result;
        }
        error_log("Error BD ({$conn->errno}): {$conn->error}");
        return false;
    }

    /**
     * Obtiene una lista de los nombres de todos los cursos disponibles.
     */
    static public function obtenerNombreCursos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT nombre_curso FROM Curso";
        $rs = $conn->query($query);
        if ($rs) {
            $result = [];
            while ($row = $rs->fetch_assoc()) {
                $result[] = $row["nombre_curso"];
            }
            $rs->free();
            return $result;
        }
        error_log("Error BD ({$conn->errno}): {$conn->error}");
        return false;
    }

    /**
     * Edita un curso existente en la base de datos.
     */
    public static function editarCurso($nombreCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Actualiza la información del curso actual en la base de datos.
     */
    public function actualizarCurso() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "UPDATE Curso SET descripcion=?, duracion=?, nivel_dificultad=?, categoria=?, precio=? WHERE nombre_curso=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssds", $this->descripcion, $this->duracion, $this->nivel_dificultad, $this->categoria, $this->precio, $this->nombre_curso);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Borra un curso de la base de datos por su nombre.
     */
    public static function borrarCurso($nombreCurso) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM Curso WHERE nombre_curso = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombreCurso);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Verifica si un profesor tiene cursos asignados.
     */
    public static function cursosDelProfe($idProfe) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        try {
            $query = "SELECT COUNT(*) FROM Curso WHERE profesor_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $idProfe);
            $stmt->execute();
            $stmt->bind_result($numCursos);
            $stmt->fetch();
            return $numCursos > 0;
        } catch (mysqli_sql_exception $e) {
            error_log("Error al contar cursos del profesor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un curso tiene alumnos inscritos.
     */
    public function tieneAlumnosInscritos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT COUNT(*) as total FROM Registrado WHERE curso_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $this->nombre_curso);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            return $row['total'] > 0;
        }
        return false;
    }

    /**
     * Establece una nueva descripción para el curso.
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * Establece una nueva duración para el curso.
     */
    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    /**
     * Establece un nuevo nivel de dificultad para el curso.
     */
    public function setNivelDificultad($nivel_dificultad) {
        $this->nivel_dificultad = $nivel_dificultad;
    }

    /**
     * Establece una nueva categoría para el curso.
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Establece un nuevo precio para el curso.
     */
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
}
