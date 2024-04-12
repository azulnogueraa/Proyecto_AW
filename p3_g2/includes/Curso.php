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
    
    
}
