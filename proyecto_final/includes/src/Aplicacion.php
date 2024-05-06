<?php

namespace es\ucm\fdi\aw;

/**
 * Clase que mantiene el estado global de la aplicación.
 */
class Aplicacion {

    private static $instancia;
    /**
     * @var array Almacena los datos de configuración de la BD
     */
    private $bdDatosConexion;
    /**
     * Almacena si la Aplicacion ya ha sido inicializada.
     * @var boolean
     */
    private $inicializada = false;
    /**
     * @var \mysqli Conexión de BD.
     */
    private $conn;

    /**
     * Constructor privado para evitar la creación directa de instancias
     */
    private function __construct() {}

    /**
     * Devuelve la instancia singleton de la clase
     * 
     * @return self La instancia única de la clase
     */
    public static function getInstance() {
        if (!self::$instancia instanceof self) {
            self::$instancia = new static();
        }
        return self::$instancia;
    }

    /**
     * Inicializa la aplicación con los datos de conexión a la base de datos
     * 
     * @param array $bdDatosConexion Datos de conexión a la base de datos
     */
    public function init($bdDatosConexion) {
        if (!$this->inicializada) {
            $this->bdDatosConexion = $bdDatosConexion;
            $this->inicializada = true;
            session_start();
        }
    }

    /**
     * Cierra la conexión a la base de datos si está abierta
     */
    public function shutdown() {
        $this->compruebaInstanciaInicializada();
        if ($this->conn !== null && !$this->conn->connect_errno) {
            $this->conn->close();
        }
    }

    /**
     * Comprueba si la aplicación ha sido inicializada
     */
    private function compruebaInstanciaInicializada() {
        if (!$this->inicializada) {
            echo "Aplicación no inicializada. Por favor, llame al método init().";
            exit();
        }
    }

    /**
     * Devuelve la conexión a la base de datos
     * 
     * @return \mysqli La conexión a la base de datos
     */
    public function getConexionBd() {
        $this->compruebaInstanciaInicializada();
        if (!$this->conn) {
            $bdHost = $this->bdDatosConexion['host'];
            $bdUser = $this->bdDatosConexion['user'];
            $bdPass = $this->bdDatosConexion['pass'];
            $bd = $this->bdDatosConexion['bd'];

            $conn = new \mysqli($bdHost, $bdUser, $bdPass, $bd);
            if ($conn->connect_errno) {
                error_log("Error de conexión a la BD ({$conn->connect_errno}): {$conn->connect_error}");
                echo "Error de conexión a la base de datos. Por favor, intente más tarde.";
                exit();
            }
            if (!$conn->set_charset("utf8mb4")) {
                error_log("Error al configurar la BD ({$conn->errno}): {$conn->error}");
                echo "Error al configurar la base de datos. Por favor, intente más tarde.";
                exit();
            }
            $this->conn = $conn;
        }
        return $this->conn;
    }
}
