<?php
    //Inicio del procesamiento
    //session_start();

    require_once __DIR__.'/includes/utils.php';
    $conn = conexionBD();

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
                echo "Ningun curso con este nombre";
            }
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
        }
    } echo "Nombre de curso no especificado";
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

        <div class="info_curso"> <!-- do not exist in css -->
            <h1>Carrera de <?=$nombre_curso?></h1>
            <p><?=$descripcion?></p>
            <a href="inscripcion.php?nombre_curso=<?=$nombre_curso?>">
                <button type="submit" name="inscribirme" value="inscribirme">Inscribirme ahora</button>
            </a>
        </div>
    </body>
</html>