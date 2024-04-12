<?php
class Curso {
    private $nombre;
    private $precio;
    private $contenido;


    public function __construct($nombre, $precio, $contenido) {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->contenido = $contenido;
    }

    public function getNombre() {
        return $this->nombre;
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
        echo "<a href='" . $this->getContenido() . "' class='button-cursos'>Ver curso</a>";
        echo "</div>";
    }
}