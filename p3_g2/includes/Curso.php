<?php
namespace es\ucm\fdi\aw;
class Curso {

    private $precio;
    private $nombre_curso;
    private $descripcion;
    //private $duracion;
    //private $lista_alumnos;

    public function __construct($nombre, $precio, $descripcion) {
        $this->nombre_curso = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
    }

    public static function crea($nombre, $precio, $descripcion){
        $curso = new Curso($nombre, $precio, $descripcion);
        return self::inserta($curso);

    }

    private static function inserta($curso){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO Curso(nombre_curso, precio, descripcion) VALUES ('%s', '%s', '%s')"
            , $conn->real_escape_string($curso->nombre_curso)                  
            , $conn->real_escape_string($curso->precio)
            , $conn->real_escape_string($curso->descripcion)
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
    public function toBox() {
        echo "<div class='box-cursos'>";
        echo "<h2 class='nombre-cursos'>" . $this->getNombre() . "</h2>";
        echo "<div class='precio-cursos'>Precio: " . $this->getPrecio() . " EUR" . "</div>";
        echo "<a href='" . $this->getDescripcion() . "' class='button-cursos'>Ver curso</a>";
        echo "</div>";
    }
    
}
