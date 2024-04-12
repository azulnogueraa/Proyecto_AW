<?php
    //Inicio del procesamiento
    //session_start();

    require_once(dirname(__FILE__) . '/../../config.php');
    $conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

    $nombre_curso = "No especificado"; // Valor predeterminado significativo
    $descripcion = ""; // Definir la variable con un valor predeterminado
    
    if(isset($_GET['nombre_curso'])) {
        $nombre_curso = $_GET['nombre_curso'];
        
        $query=sprintf("SELECT * FROM Curso WHERE nombre_curso = '%s'"
            , $nombre_curso
        );
        $rs = $conn->query($query);
        if($rs) {
            if($rs->num_rows > 0) {
                while($row = $rs->fetch_assoc()) {
                    $descripcion = $row["descripcion"];
                }
            } else {
                echo "Ningún curso con este nombre";
            }
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head> 
        <meta charset="utf-8">
        <title><?=$nombre_curso?></title>
        <link rel="stylesheet" href="CSS/curso_vista.css">
        <link rel="stylesheet" href="CSS/topBar.css">
    </head>
    <body>
        <?php require "includes/vistas/comun/topbar.php"; ?>

        <div class="info_curso">
            <h1>Carrera de <?=$nombre_curso?></h1>
            <p><?=$descripcion?></p>
            <a href="inscripcion.php?nombre_curso=<?=$nombre_curso?>">
                <button type="submit" name="inscribirme" value="inscribirme">Inscribirme ahora</button>
            </a>
        </div>
    </body>
</html>
