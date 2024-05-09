<?php
namespace es\ucm\fdi\aw;
abstract class Usuario {
    public const ADMIN_ROLE = 1;
    public const ESTUDIANTE_ROLE = 2;
    public const PROFESOR_ROLE = 3;
    public static function login($nombre_usuario, $contrasena) {
        $result = static::buscaUsuario($nombre_usuario);
        $usuario = $result;
        if ($usuario && $usuario->compruebaPassword($contrasena)) {
            return $usuario;
        }
        return false;
    }
    abstract public static function crea($nombre_usuario, $apellido, $email, $contrasena);
    protected static function creaUsuario($class, $nombre_usuario, $apellido, $email, $contrasena) {
        $user = new $class($nombre_usuario, $apellido, $email, self::hashPassword($contrasena));
        return $user->guarda();
    }
    public static function buscaUsuario($nombre_usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $tables = ['Estudiante', 'Profesor', 'Administrador'];
        $result = false;
        foreach ($tables as $table) {
            $query = sprintf("SELECT * FROM %s WHERE nombre_usuario='%s'",
                $table,
                $conn->real_escape_string($nombre_usuario)
            );
            $rs = $conn->query($query);
            
            if ($rs && $rs->num_rows > 0) {
                $fila = $rs->fetch_assoc();
                switch ($table) {
                    case 'Estudiante':
                        $result = new Estudiante($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                    case 'Profesor':
                        $result = new Profesor($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                    case 'Administrador':
                        $result = new Admin($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                }
                $rs->free();
                break;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
            }
        }
        return $result;
    }

    public static function buscaUsuarioPorId($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $tables = ['Estudiante', 'Profesor', 'Administrador'];
        $result = false;
    
        foreach ($tables as $table) {
            $query = sprintf("SELECT * FROM %s WHERE id = %d",
                $table,
                $id
            );
    
            $rs = $conn->query($query);
    
            if ($rs && $rs->num_rows > 0) {
                $fila = $rs->fetch_assoc();
                switch ($table) {
                    case 'Estudiante':
                        $result = new Estudiante($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                    case 'Profesor':
                        $result = new Profesor($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                    case 'Administrador':
                        $result = new Admin($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                        break;
                }
                $rs->free();
                break;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
            }
        }
    
        return $result;
    }
    
    
    private static function hashPassword($contrasena) {
        return password_hash($contrasena, PASSWORD_DEFAULT);
    }
    abstract public static function inserta($usuario);
    protected static function insertaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO %s(nombre_usuario, apellido, email, contrasena) VALUES ('%s', '%s', '%s', '%s')",
            $table,
            $conn->real_escape_string($usuario->nombre_usuario),
            $conn->real_escape_string($usuario->apellido),
            $conn->real_escape_string($usuario->email),
            $conn->real_escape_string($usuario->contrasena)
        );

        if ($conn->query($query)) {
            $usuario->id = $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }
    abstract protected static function actualiza($usuario);
    public static function actualizaUsuario($table, $usuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE %s U SET nombre_usuario = '%s', apellido = '%s', email='%s', contrasena='%s' WHERE U.id=%d"
            , $table
            , $conn->real_escape_string($usuario->nombre_usuario)
            , $conn->real_escape_string($usuario->apellido)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->contrasena)
            , $usuario->id
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }
    abstract public static function borra($usuario);
    public static function borraUsuario($table, $usuario) {
        if ($usuario->id !== null) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("DELETE FROM %s WHERE id = %d"
                , $table
                , $usuario->id
            );
            if ( ! $conn->query($query) ) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    private $id;
    private $nombre_usuario;
    private $apellido;
    private $email;
    private $contrasena;
    public function __construct($nombre_usuario, $apellido, $email, $contrasena, $id = null) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }
    public function compruebaPassword($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }
    public function guarda() {
        if ($this->id !== null) {
            return static::actualiza($this);
        }
        return static::inserta($this);
    }
    public function getId() {
        return $this->id;
    }
    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getEmail() {
        return $this->email;
    }

    public static function cambiarRol($nombreUsuario, $nuevoRol) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $usuario = self::buscaUsuario($nombreUsuario);
    
        if (!$usuario) {
            return "Usuario no encontrado.";
        }
    
        // Determinar el rol actual del usuario
        $rolActual = $usuario->getRol();
    
        // Verificar si se intenta cambiar a un rol que ya tiene el usuario
        if ($rolActual === $nuevoRol) {
            return "error_mismo_rol";
        }
    
        // Verificar si se puede realizar el cambio de rol
        if (($rolActual === 'Estudiante' && $nuevoRol === 'Profesor') ||
            ($rolActual === 'Profesor' && $nuevoRol === 'Estudiante')) {
    
            // Verificar si el cambio de profesor a estudiante está permitido
            if ($rolActual === 'Profesor' && $nuevoRol === 'Estudiante') {
                $nombreUsuario = $usuario->getNombreUsuario();
                $tieneCursos = Profesor::cursosDelProfesor($$nombreUsuario);
    
                if ($tieneCursos) {
                    return "error_profesor_con_cursos";
                }
            }

            // Verificar si el cambio de estudiante a profesor está permitido
            if ($rolActual === 'Estudiante' && $nuevoRol === 'Profesor') {
                $cursosAsignados = self::getCursosAsignados($nombreUsuario);
    
                if ($cursosAsignados) {
                    return "error_estudiante_con_cursos";
                }
            }
    
            // Eliminar al usuario de la tabla del rol anterior
            $eliminado = self::borraUsuario($rolActual, $usuario);
            if (!$eliminado) {
                return "error_eliminar_usuario_anterior";
            }
    
            // Insertar al usuario en la tabla del nuevo rol
            if ($nuevoRol === 'Profesor') {
                $insertado = Profesor::inserta($usuario);
            } else {
                $insertado = Estudiante::inserta($usuario);
            }
    
            if ($insertado) {
                return "exito"; // Indica que el cambio de rol fue exitoso
            } else {
                return "Error al cambiar el rol del usuario <strong>$nombreUsuario</strong>.";
            }
        } else {
            return "El cambio de rol no está permitido.";
        }
    }    

    public function getRol() {
        // Determinar el rol del usuario basado en el nombre de la tabla
        $tableName = $this->getTableName(); // Método para obtener el nombre de la tabla
        switch ($tableName) {
            case 'Estudiante':
                return 'Estudiante';
            case 'Profesor':
                return 'Profesor';
            // Agrega más casos según los nombres de tus tablas y sus correspondientes roles
            default:
                return 'Desconocido';
        }
    }

    private function getTableName() {
        // Implementa lógica para obtener el nombre de la tabla según la instancia actual
        if ($this instanceof Estudiante) {
            return 'Estudiante';
        } elseif ($this instanceof Profesor) {
            return 'Profesor';
        }
        // Agrega más condiciones según las subclases que tengas
        return 'Desconocido';
    }

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

    public function setNombre($nombre) {
        $this->nombre_usuario = $nombre;
    }
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setContrasena($contrasena) {
        $this->contrasena = self::hashPassword($contrasena);
    }

    public function actualizarUsuario() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "UPDATE Estudiante SET nombre_usuario=?, apellido=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $this->nombre_usuario, $this->apellido, $this->email, $this->id);
    
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    
}