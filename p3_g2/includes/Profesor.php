<?php
namespace es\ucm\fdi\aw;
class Profesor extends Usuario {

    public static function crea($nombre_usuario, $apellido, $email, $contrasena) {
        return self::creaUsuario(__CLASS__, $nombre_usuario, $apellido, $email, $contrasena);
    }
    public static function busca($nombre_usuario) {
        $table = 'Profesor';
        return self::buscaUsuario($table, $nombre_usuario);
    }
    public static function inserta($usuario) {
        $table = 'Profesor';
        return self::insertaUsuario($table, $usuario);
    }
    protected static function actualiza($usuario) {
        $table = 'Profesor';
        return self::actualizaUsuario($table, $usuario);
    }
    public function borra($usuario) {
        $table = 'Profesor';
        return self::borraUsuario($table, $usuario);
    }
}