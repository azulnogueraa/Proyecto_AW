<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Cursos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/logo.jpg" type="image/png">
        <link rel="stylesheet" href="CSS/cursos.css">
    </head>
    <body>
        <!-- topbar -->
        <?php 
            require_once "includes/vistas/comun/box_curso.php";
            require "includes/vistas/comun/topbar.php";

            // Sólo un ejemplo aquí, que se cambiará cuando tengamos la base de datos
            $cursos = [
                new Curso("Criptomonedas", "44 EUR", "cripto.php"),
                new Curso("Trading", "35 EUR", "trading.php"),
                new Curso("Blockchain", "52 EUR", "blockchain.php"),
                new Curso("Marketing", "60 EUR", "marketing.php"),
            ];

            echo "<div class='container'>";
            foreach ($cursos as $curso) {
                $curso->toBox();
            }
            echo "</div>";
        ?>
    </body>
</html>