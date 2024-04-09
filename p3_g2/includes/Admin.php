<?php
namespace es\ucm\fdi\aw;
class Admin extends Usuario {

    public static function crea($nombre_usuario, $apellido, $email, $contrasena, $rol) {
        return self::creaUsuario(__CLASS__, $nombre_usuario, $apellido, $email, $contrasena);
    }
    public static function busca($nombre_usuario) {
        $table = 'Administrador';
        return self::buscaUsuario($table, $nombre_usuario);
    }
    public static function inserta($usuario) {
        $table = 'Administrador';
        return self::insertaUsuario($table, $usuario);
    }
    protected static function actualiza($usuario) {
        $table = 'Administrador';
        return self::actualizaUsuario($table, $usuario);
    }
    public function borra($usuario) {
        $table = 'Administrador';
        return self::borraUsuario($table, $usuario);
    }
}