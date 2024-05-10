<?php
namespace es\ucm\fdi\aw;
abstract class Usuario {
    public const ADMIN_ROLE = 1;
    public const ESTUDIANTE_ROLE = 2;
    public const PROFESOR_ROLE = 3;

    /**
     * Comprueba si un usuario está registrado en la aplicación y la contraseña es correcta
     * @param string $nombre_usuario Nombre de usuario
     * @param string $contrasena Contraseña
     * @return Usuario|false Usuario si las credenciales son correctas, false en caso contrario
     */
    public static function login($nombre_usuario, $contrasena) {
        $result = static::buscaUsuario($nombre_usuario);
        $usuario = $result;
        if ($usuario && $usuario->compruebaPassword($contrasena)) {
            return $usuario;
        }
        return false;
    }

    /**
     * Crea un nuevo usuario
     * @param string $nombre_usuario Nombre de usuario
     * @param string $apellido Apellido
     * @param string $email Email
     * @param string $contrasena Contraseña
     * @return Usuario|false Usuario si se ha creado correctamente, false en caso contrario
     */
    abstract public static function crea($nombre_usuario, $apellido, $email, $contrasena);

    protected static function creaUsuario($class, $nombre_usuario, $apellido, $email, $contrasena) {
        $user = new $class($nombre_usuario, $apellido, $email, self::hashPassword($contrasena));
        return $user->guarda();
    }

    /**
     * Busca un usuario por su nombre de usuario
     * @param string $nombre_usuario Nombre de usuario
     * @return Usuario|false Usuario si se ha encontrado, false en caso contrario
     */
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

    /**
     * Busca un usuario por su ID
     * @param string $tabla Nombre de la tabla
     * @param int $id ID del usuario
     * @return Usuario|false Usuario si se ha encontrado, false en caso contrario
     */
    public static function buscaUsuarioPorId($tabla, $id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $result = false;
        $query = sprintf("SELECT * FROM %s WHERE id = %d",
            $tabla,
            $id
        );
        $rs = $conn->query($query);
        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
            switch ($tabla) {
                case 'Estudiante':
                    $result = new Estudiante($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                    break;
                case 'Profesor':
                    $result = new Profesor($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                    break;
                case 'Administrador':
                    $result = new Admin($fila['nombre_usuario'], $fila['apellido'], $fila['email'], $fila['contrasena'], $fila['id']);
                    break;
                default:
                    error_log("Tabla desconocida: {$tabla}");
                    break;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    /**
     * Hash de la contraseña
     */
    private static function hashPassword($contrasena) {
        return password_hash($contrasena, PASSWORD_DEFAULT);
    }

    /**
     * Inserta un usuario en la base de datos
     * @param Usuario $usuario Usuario a insertar
     * @return Usuario|false Usuario si se ha insertado correctamente, false en caso contrario
     */
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

    /**
     * Actualiza un usuario en la base de datos
     * @param Usuario $usuario Usuario a actualizar
     * @return Usuario|false Usuario si se ha actualizado correctamente, false en caso contrario
     */
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

    /** 
     * Borra un usuario de la base de datos
     * @param string $tabla Nombre de la tabla
     * @param Usuario $usuario Usuario a borrar
     * @return boolean true si se ha borrado correctamente, false en caso contrario
    */
    public static function borraUsuario($tabla, $usuario) {
        if ($usuario->id !== null) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("DELETE FROM %s WHERE id = %d"
                , $tabla
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

    /**
     * Constructor de la clase Usuario
     */
    public function __construct($nombre_usuario, $apellido, $email, $contrasena, $id = null) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }
    /**
     * Comprueba si la contraseña es correcta
     * @param string $contrasena Contraseña
     */
    public function compruebaPassword($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }
    /**
     * Guarda un usuario en la base de datos
     */
    public function guarda() {
        if ($this->id !== null) {
            return static::actualiza($this);
        }
        return static::inserta($this);
    }
    /**
     * Devuelve el ID del usuario
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Devuelve el nombre de usuario
     */
    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    /**
     * Devuelve el apellido del usuario
     */
    public function getApellido() {
        return $this->apellido;
    }

    /**
     * Devuelve el email del usuario
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Establece el nombre de usuario
     */
    public function setNombre($nombre) {
        $this->nombre_usuario = $nombre;
    }

    /**
     * Establece el apellido del usuario
     */
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    /**
     * Establece el email del usuario
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Establece la contraseña del usuario
     */
    public function setContrasena($contrasena) {
        $this->contrasena = self::hashPassword($contrasena);
    }

    /**
     * Cambia el rol de un usuario
     * @param string $nombreUsuario Nombre de usuario
     * @param string $nuevoRol Nuevo rol
     * @return string Mensaje de éxito o error
     */
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
                $tieneCursos = Profesor::cursosDelProfesor($nombreUsuario);
    
                if ($tieneCursos > 0) {
                    return "error_profesor_con_cursos";
                }
            }

            // Verificar si el cambio de estudiante a profesor está permitido
            if ($rolActual === 'Estudiante' && $nuevoRol === 'Profesor') {
                $cursosAsignados = Estudiante::getCursosAsignados($nombreUsuario);
    
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

    /**
     * Devuelve el rol del usuario
     * @return string Rol del usuario o 'Desconocido' si no se puede determinar
     */
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

    /**
     * Devuelve el nombre de la tabla según la instancia actual
     */
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
}