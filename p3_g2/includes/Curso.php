<?php
namespace es\ucm\fdi\aw;
class Curso {

    private $precio;
    private $nombre_curso;
    private $descripcion;
    private $duracion;
    private $categoria;
    private $nivel_dificultad;
    // private $lista_alumnos;

    public function __construct($nombre, $precio, $descripcion, $duracion, $categoria, $nivel_dificultad) {
        $this->nombre_curso = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->duracion = $duracion;
        $this->categoria = $categoria;
        $this->nivel_dificultad = $nivel_dificultad;
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

    public function toBox() {
        $nombre = htmlspecialchars($this->getNombre());
        $precio = htmlspecialchars($this->getPrecio());
        $descripcion = htmlspecialchars($this->getDescripcion());
    
        echo "<div class='box-cursos'>";
        echo "<h2 class='nombre-cursos'>$nombre</h2>";
        echo "<div class='precio-cursos'>Precio: $precio EUR</div>";
        echo "<p class='descripcion-cursos'>$descripcion</p>";
        echo "<a href='curso.php?nombre_curso=$nombre' class='button-cursos'>Ver curso</a>";
        echo "</div>";
    }
    

    static public function obtenerCursos() {
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

    public function actualizar() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "UPDATE Curso SET descripcion=?, duracion=?, nivel_dificultad=?, categoria=?, precio=? WHERE nombre_curso=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssds", $this->descripcion, $this->duracion, $this->nivel_dificultad, $this->categoria, $this->precio, $this->nombre_curso);

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

}
