<?php
namespace es\ucm\fdi\aw;
class Profesor extends Usuario {

    public static function crea($nombre_usuario, $apellido, $email, $contrasena) {
        return self::creaUsuario(__CLASS__, $nombre_usuario, $apellido, $email, $contrasena);
    }
    public static function inserta($usuario) {
        $table = 'Profesor';
        return self::insertaUsuario($table, $usuario);
    }
    protected static function actualiza($usuario) {
        $table = 'Profesor';
        return self::actualizaUsuario($table, $usuario);
    }
    public static function borra($usuario) {
        $table = 'Profesor';
        return self::borraUsuario($table, $usuario);
    }
    public function misCursos() {
        $idProfe = $this->getId();
        return true; //A cambiar por eso cuando tenemos la clase : Curso::cursosDelProfe($idProfe);
    }
}