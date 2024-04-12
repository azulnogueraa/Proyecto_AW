<!-- 
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Cursos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/style.css">
    </head>
    <body>-->
        <!-- topbar -->
        <?php /*
            require_once "includes/vistas/comun/box_curso.php";
            require "includes/vistas/comun/topbar.php";

            // Sólo un ejemplo aquí, que se cambiará cuando tengamos la base de datos
            $cursos = [
                new Curso("Criptomonedas", "44", "lista_cursos/cripto.php"),
                new Curso("Trading", "35", "lista_cursos/trading.php"),
                new Curso("Blockchain", "52", "lista_cursos/blockchain.php"),
                new Curso("Marketing", "60", "lista_cursos/marketing.php"),
                // new Curso("Programación", "58", "lista_cursos/programación.php"),
            ];

            echo "<div class='container-cursos'>";
            foreach ($cursos as $curso) {
                $curso->toBox();
            }
            echo "</div>";*/
        ?>
   <!-- </body>
</html> -->

<?php
require_once __DIR__.'/includes/config.php';
//require_once "includes/vistas/comun/box_curso.php";
//aqui intento de ver si se hace en config.php
//en buscar_cursos he puesto include Curso.php para ver cual funciona mejor
$tituloPagina = 'Cursos';

// $cursos = [
//     new Curso("Criptomonedas", "44", "lista_cursos/cripto.php"),
//     new Curso("Trading", "35", "lista_cursos/trading.php"),
//     new Curso("Blockchain", "52", "lista_cursos/blockchain.php"),
//     new Curso("Marketing", "60", "lista_cursos/marketing.php"),
//     // new Curso("Programación", "58", "lista_cursos/programación.php"),
// ];

//no seguro que eso se debe llamar en este script, quizas se hace directamente en una clase 
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();//quizas ya se hace en config.php
$query = printf("SELECT * FROM Curso C");
$cursos = $conn->query($query);

$contenidoPrincipal = <<<EOS
<div class='container-cursos'>
EOS;

foreach ($cursos as $curso) {
    $contenidoPrincipal .= $curso->toBox();
}

$contenidoPrincipal .= <<<EOS
</div>
EOS;
include 'includes/vistas/plantillas/plantilla.php';