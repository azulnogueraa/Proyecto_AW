<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
	<link rel="icon" href="<?= RUTA_IMGS ?>/logo.jpg" type="image/png">
	<link rel="stylesheet" href="<?= RUTA_CSS ?>/style.css">
</head>
<body>
<div id="contenedor">
<?php
require "includes/vistas/comun/topbar.php";
?>
<main>
<article>
<?= $contenidoPrincipal ?>
</article>
</main>
</div>
</body>
</html>
