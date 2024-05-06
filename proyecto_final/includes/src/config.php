<?php

spl_autoload_register(function ($class_name) {
    // Define el prefijo del namespace y el directorio base
    $prefix = 'es\\ucm\\fdi\\aw\\';
    $base_dir = __DIR__.'/';

    // Comprueba si la clase utiliza el prefijo del namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class_name, $len) !== 0) {
        // Si no usa el prefijo, deja que otra función de carga de clases se encargue
        return;
    }

    // Obtiene el nombre relativo de la clase
    $relative_class = substr($class_name, $len);

    // Reemplaza el prefijo del namespace con el directorio base, cambia las barras invertidas por barras inclinadas
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo de clase existe, cárgalo
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', 'localhost'); // necesitar cambiar eso cuando estaremos en el servidor
define('BD_NAME', 'learnique');
define('BD_USER', 'learnique');
define('BD_PASS', 'learnique');

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '');
define('RUTA_IMGS', RUTA_APP.'img');
define('RUTA_CSS', RUTA_APP.'CSS');
define('RUTA_JS', RUTA_APP.'js');

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

// Inicializa la aplicación
$app = es\ucm\fdi\aw\Aplicacion::getInstance();
$app->init(['host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS]);

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function([$app, 'shutdown']);