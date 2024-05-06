<?php
namespace es\ucm\fdi\aw;

abstract class Usuario {
    private $id;
    private $nombre_usuario;
    private $apellido;
    private $email;
    private $contrasena;

    public function __construct($id, $nombre_usuario, $apellido, $email, $contrasena) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }

    public static function creaUsuario($tipo, $nombre_usuario, $apellido, $email, $contrasena) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO %s (nombre_usuario, apellido, email, contrasena) VALUES (?, ?, ?, ?)",
            $tipo
        );
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $nombre_usuario, $apellido, $email, password_hash($contrasena, PASSWORD_DEFAULT));
        $result = $stmt->execute();
        if ($result) {
            $id = $stmt->insert_id;
            return new static($id, $nombre_usuario, $apellido, $email, $contrasena);
        } else {
            return false;
        }
    }

    protected static function insertaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO %s (nombre_usuario, apellido, email, contrasena) VALUES (?, ?, ?, ?)",
            $table
        );
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $usuario->nombre_usuario, $usuario->apellido, $usuario->email, $usuario->contrasena);
        return $stmt->execute();
    }

    protected static function actualizaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "UPDATE %s SET nombre_usuario=?, apellido=?, email=?, contrasena=? WHERE id=?",
            $table
        );
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'ssssi',
            $usuario->nombre_usuario,
            $usuario->apellido,
            $usuario->email,
            $usuario->contrasena,
            $usuario->id
        );
        return $stmt->execute();
    }

    protected static function borraUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM %s WHERE id=?", $table);
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $usuario->id);
        return $stmt->execute();
    }
}
