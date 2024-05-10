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

    /**
     * Obtener todos los nombres de profesores
     * @return array Nombres de profesores
     */
    public static function obtenerNombres()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Consulta SQL para obtener todos los profesores
        $query = "SELECT nombre_usuario FROM Profesor";

        try {
            $result = $conn->query($query);

            if ($result) {
                $profesores = [];

                // Recorrer los resultados y crear objetos Profesor
                while ($row = $result->fetch_assoc()) {
                    $profesor = $row['nombre_usuario'];
                    $profesores[] = $profesor;
                }

                $result->free(); // Liberar resultado

                return $profesores;
            } else {
                error_log("Error al obtener todos los profesores: " . $conn->error);
                return [];
            }
        } catch (\mysqli_sql_exception $e) {
            error_log("Excepción al obtener todos los profesores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener el ID de un profesor por su nombre
     */
    public static function obtenerIdporNombre($nombre_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Consulta SQL para obtener el ID del profesor por su nombre
        $query = "SELECT id FROM Profesor WHERE nombre_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();

        return $id;
    }
}