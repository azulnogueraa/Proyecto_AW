<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
	<link rel="icon" href="<?= RUTA_IMGS ?>/logo.jpg" type="image/png">
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/login_registro.css" />
	<link rel="stylesheet" href="CSS/topBar.css">
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
<?php
?>
</div>
</body>
</html>
