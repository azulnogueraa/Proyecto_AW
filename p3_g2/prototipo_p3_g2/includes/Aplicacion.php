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

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instancia instanceof self) {
			self::$instancia = new static();
		}
		return self::$instancia;
    }

    public function init($bdDatosConexion) {
        if ( ! $this->inicializada ) {
    	    $this->bdDatosConexion = $bdDatosConexion;
    		$this->inicializada = true;
    		session_start();
        }
    }

    public function shutdown() {
	    $this->compruebaInstanciaInicializada();
	    if ($this->conn !== null && ! $this->conn->connect_errno) {
	        $this->conn->close();
	    }
	}

    private function compruebaInstanciaInicializada() {
	    if (! $this->inicializada ) {
	        echo "Aplicacion no inicializa";
	        exit();
	    }
	}

    public function getConexionBd()
	{
	    $this->compruebaInstanciaInicializada();
		if (! $this->conn ) {
			$bdHost = $this->bdDatosConexion['host'];
			$bdUser = $this->bdDatosConexion['user'];
			$bdPass = $this->bdDatosConexion['pass'];
			$bd = $this->bdDatosConexion['bd'];
			
			$conn = new \mysqli($bdHost, $bdUser, $bdPass, $bd);
			if ( $conn->connect_errno ) {
				echo "Error de conexión a la BD ({$conn->connect_errno}):  {$conn->connect_error}";
				exit();
			}
			if ( ! $conn->set_charset("utf8mb4")) {
				echo "Error al configurar la BD ({$conn->errno}):  {$conn->error}";
				exit();
			}
			$this->conn = $conn;
		}
		return $this->conn;
	}
}