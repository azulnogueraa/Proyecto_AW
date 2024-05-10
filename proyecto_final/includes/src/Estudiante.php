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

    /**
     * Devuelve los cursos asignados al estudiante
     * @param string $nombre_usuario Nombre de usuario
     * @return Curso[] Array de objetos Curso
     */
    public static function getCursosAsignados($nombre_usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $cursos = [];
    
        // Consulta SQL para obtener los cursos asignados al usuario
        $query = sprintf("SELECT c.* FROM Curso c 
                          INNER JOIN Registrado r ON c.nombre_curso = r.curso_id
                          INNER JOIN Estudiante e ON r.u_id = e.id
                          WHERE e.nombre_usuario = '%s'",
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
}