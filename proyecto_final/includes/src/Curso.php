<?php
namespace es\ucm\fdi\aw;
class Curso {

    private $nombre_curso;
    private $descripcion;
    private $profesor_id;
    private $duracion;
    private $categoria;
    private $nivel_dificultad;
    private $precio;

    /**
     * Constructor de la clase Curso
     */
    public function __construct($nombre_curso, $descripcion, $profesor_id, $duracion, $categoria, $nivel_dificultad, $precio) {
        $this->nombre_curso = $nombre_curso;
        $this->descripcion = $descripcion;
        $this->profesor_id = $profesor_id;
        $this->duracion = $duracion;
        $this->categoria = $categoria;
        $this->nivel_dificultad = $nivel_dificultad;
        $this->precio = $precio;
    }

    /**
     * Crear un nuevo curso en la base de datos
     * @return bool Devuelve true si se ha creado correctamente, o false si ha habido un error
     */
    public static function crearCurso($nombre_curso, $descripcion, $profesorId, $duracion, $categoria, $nivelDificultad, $precio) {
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $query = "INSERT INTO Curso (nombre_curso, descripcion, profesor_id, duracion, nivel_dificultad, categoria, precio, fecha_creacion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp())";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissss", $nombre_curso, $descripcion, $profesorId, $duracion, $nivelDificultad, $categoria, $precio);

        $result = $stmt->execute();
    
        if ($result === false) {
            error_log("Error al insertar curso: " . $stmt->error);
            return false;
        }
    
        $stmt->close();
    
        return true;
    }

    /**
     * Buscar un curso por su nombre
     * @param $nombreCurso Nombre del curso a buscar
     * @return Curso|null Devuelve un objeto Curso si se encuentra, o null si no se encuentra
     */
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
            $curso = new Curso($row['nombre_curso'], $row['descripcion'], $row['profesor_id'], $row['duracion'], $row['categoria'], $row['nivel_dificultad'], $row['precio']);
            return $curso;
        }

        return null; // Si no se encuentra ningún curso con ese nombre
    }

    /**
     * Buscar un curso por su ID
     * @param $idCurso ID del curso a buscar
     * @return Curso|null Devuelve un objeto Curso si se encuentra, o null si no se encuentra
     */
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
            $curso = new Curso($row['nombre_curso'], $row['descripcion'], $row['profesor_id'], $row['duracion'], $row['categoria'], $row['nivel_dificultad'], $row['precio']);
            return $curso;
        }
    
        return null; // Si no se encuentra ningún curso con ese ID
    }
    
    /**
     * Obtener el nombre del curso
     * @return string Nombre del curso
     */
    public function getNombre() {
        return $this->nombre_curso;
    }

    /**
     * Obtener la descripción del curso
     * @return string Descripción del curso
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Obtener el ID del profesor que imparte el curso
     * @return int ID del profesor
     */
    public function getProfesorId() {
        return $this->profesor_id;
    }

   /**
     * Obtener la categoría del curso
     * @return string Categoría del curso
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Obtener la duración del curso
     * @return string Duración del curso
     */
    public function getDuracion() {
        return $this->duracion;
    }

    /**
     * Obtener el nivel de dificultad del curso
     * @return string Nivel de dificultad del curso
     */
    public function getNivelDificultad() {
        return $this->nivel_dificultad;
    }

    /**
     * Obtener el precio del curso
     * @return float Precio del curso
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Establecer la descripción del curso
     * @param string $descripcion Descripción del curso
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * Establecer el ID del profesor que imparte el curso
     * @param int $profesor_id ID del profesor
     */
    public function setProfesorId($profesor_id) {
        $this->profesor_id = $profesor_id;
    }

    /**
     * Establecer la duración del curso
     * @param string $duracion Duración del curso
     */
    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    /**
     * Establecer el nivel de dificultad del curso
     * @param string $nivel_dificultad Nivel de dificultad del curso
     */
    public function setNivelDificultad($nivel_dificultad) {
        $this->nivel_dificultad = $nivel_dificultad;
    }

    /**
     * Establecer la categoría del curso
     * @param string $categoria Categoría del curso
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Establecer el precio del curso
     * @param float $precio Precio del curso
     */
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    /**
     * Obtener todos los cursos de la base de datos
     * @return array Devuelve un array con todos los cursos, o false si hay un error
     */
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
                    $row['descripcion'],
                    $row['profesor_id'],
                    $row['duracion'],
                    $row['categoria'],
                    $row['nivel_dificultad'],
                    $row['precio']);
                $result[] = $curso;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Obtener los nombres de los cursos de la base de datos
     * @return array Devuelve un array con los nombres de los cursos, o false si hay un error
     */
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

    /**
     * Editar un curso por su nombre
     * @param $nombreCurso Nombre del curso a editar
     * @return array|null Devuelve un array con los datos del curso si se encuentra, o null si no se encuentra
     */
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

    /**
     * Actualizar un curso en la base de datos
     * @return bool Devuelve true si se ha actualizado correctamente, o false si ha habido un error
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
     * Borrar un curso por su nombre
     * @param $nombreCurso Nombre del curso a borrar
     * @return bool Devuelve true si se ha borrado correctamente, o false si ha habido un error
     */
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

    /**
     * Obtener los cursos de un profesor por su ID
     * @param $profesor_id ID del profesor
     * @return array Devuelve un array con los cursos del profesor, o null si no hay cursos
     */
    public static function obtenerCursosPorProfesor($profesor_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM Curso WHERE profesor_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $cursos = [];
            while ($row = $result->fetch_assoc()) {
                $curso = new Curso($row['nombre_curso'], $row['descripcion'], $row['profesor_id'], $row['duracion'], $row['categoria'], $row['nivel_dificultad'], $row['precio']);
                $cursos[] = $curso;
            }
            return $cursos;
        }
        return [];
    }

    //TODO a enlever : pas logique
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
