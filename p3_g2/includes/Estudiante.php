<?php
namespace es\ucm\fdi\aw;
class Estudiante extends Usuario {

    public static function crea($nombre_usuario, $apellido, $email, $contrasena) {
        return self::creaUsuario(__CLASS__, $nombre_usuario, $apellido, $email, $contrasena);
    }
    public static function inserta($usuario) {
        $table = 'Estudiante';
        return self::insertaUsuario($table, $usuario);
    }
    protected static function actualiza($usuario) {
        $table = 'Estudiante';
        return self::actualizaUsuario($table, $usuario);
    }
    public static function borra($usuario) {
        $table = 'Estudiante';
        return self::borraUsuario($table, $usuario);
    }
}