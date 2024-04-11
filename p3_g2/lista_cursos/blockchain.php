<?php
require_once '../includes/config.php';
?>

<head> 
    <meta charset="utf-8">
    <title>Blockchain</title>
    <link rel="stylesheet" href="../CSS/style.css">
    

</head>

<body>
    <!-- topbar -->
    <?php require RAIZ_APP."/vistas/comun/topbar.php"; ?>

    <div class="contenedor_vista_cursos">
        <h1>Carrera de Blockchain</h1>
        <!-- lista desorenada -->
        <ul>
            <li> Correción de proyectos prácticos</li>
            <li> Tutoría avanzada </li>
            <li> Certificado de finalización </li>
            <li> Acceso a la comunidad de estudiantes </li>
        </ul>
        <a href="inscripcion.php">
            <button type="submit" name="inscribirme" value="inscribirme">Inscribirme ahora</button>
        </a> 
    </div>
    
</body>

