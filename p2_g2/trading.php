<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<head> 
    <meta charset="utf-8">
    <title>Marketing</title>
    <link rel="stylesheet" href="CSS/curso_vista.css">
    

</head>

<body>
    <!-- topbar -->
    <?php require "includes/vistas/comun/topbar.php"; ?>

    <div class="info_marketing">
        <h1>Carrera de Trading</h1>
        <!-- lista desorenada -->
        <ul>
            <li> Correción de proyectos prácticos</li>
            <li> Tutoría avanzada </li>
            <li> Certificado de finalización </li>
            <li> Acceso a la comunidad de estudiantes </li>
        </ul>
        <button type="submit" name="inscribirme" value="inscribirme">Inscribirme ahora</button>
        
    </div>
    
</body>

</html>

