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
    public function misCursos($nombre_usuario) {
        if (self::cursosDelProfesor($nombre_usuario) > 0) {
            return true;
        } else {
            return false;
        }
    }

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
    
    public static function cursosDelProfesor($nombre_usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $cursos = [];
        // Consulta SQL para obtener los cursos asignados al usuario
        $query = sprintf("SELECT c.* FROM Curso c 
                          INNER JOIN Registrado r ON c.nombre_curso = r.curso_id
                          INNER JOIN Profesor p ON r.p_id = p.id
                          WHERE p.nombre_usuario = '%s'",
                          $conn->real_escape_string($nombre_usuario));
    
        $rs = $conn->query($query);
    
        if ($rs && $rs->num_rows > 0) {
            // Recorrer los resultados y crear objetos Curso
            while ($row = $rs->fetch_assoc()) {
                // Crear un objeto Curso con los datos recuperados
                $curso = new Curso($row['nombre_curso'], $row['descripcion'], $row['profesor_id'], $row['duracion'], $row['categoria'], $row['nivel_dificultad'], $row['precio']);
                $cursos[] = $curso; // Agregar el curso al array de cursos
            }
    
            $rs->free(); // Liberar los resultados
        }
    
        return $cursos;
    }
    
    
    public static function buscaProfesorPorId($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $result = false;
    
        // Consulta SQL para buscar en la tabla de profesores
        $query = sprintf("SELECT * FROM Profesor WHERE id = %d", $id);
    
        $rs = $conn->query($query);
    
        if ($rs && $rs->num_rows > 0) {
            // Si se encuentra un profesor con el ID especificado, crear un objeto Profesor
            $fila = $rs->fetch_assoc();
            $result = new Profesor($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'],$fila['id']);
            $rs->free();
        } else {
            // Si no se encuentra un profesor con ese ID, registrar un mensaje de error
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
    
        return $result;
    }
    

}