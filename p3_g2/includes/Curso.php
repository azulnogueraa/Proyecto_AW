<?php
namespace es\ucm\fdi\aw;
class Curso {

    private $precio;
    private $nombre_curso;
    private $contenido;
    //private $duracion;
    //private $lista_alumnos;

    public function __construct($nombre, $precio) {
        $this->nombre_curso = $nombre;
        $this->precio = $precio;
        //$this->contenido = $contenido;
    }

    public static function crea($nombre, $precio){
        $curso = new Curso($nombre, $precio);
        return self::inserta($curso);

    }

    private static function inserta($curso){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO Curso(nombre_curso, precio) VALUES ('%s', '%s')"
            , $conn->real_escape_string($curso->nombre_curso)                  //, '%s'
            , $conn->real_escape_string($curso->precio)
            //, $conn->real_escape_string($curso->contenido)
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
    public function getContenido() {
        return $this->contenido;
    }
    public function toBox() {
        echo "<div class='box-cursos'>";
        echo "<h2 class='nombre-cursos'>" . $this->getNombre() . "</h2>";
        echo "<div class='precio-cursos'>Precio: " . $this->getPrecio() . " EUR" . "</div>";
        //echo "<a href='" . $this->getContenido() . "' class='button-cursos'>Ver curso</a>";
        echo "</div>";
    }
    
}
