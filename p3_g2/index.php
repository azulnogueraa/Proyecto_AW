<?php
//Inicio del procesamiento
//session_start(); normalmente se hace en config.php
include 'includes/config.php';
$tituloPagina = 'Pagina Principal';

$contenidoPrincipal = <<<EOS
  <div class="caja">
      <div class="texto"> 
        <h1> Cursos Online para transformar tu realidad en tiempo récord</h1>
        <p>Clases en línea y en vivo dictadas por referentes de la industria, 
          enfoque 100% práctico, mentorías personalizadas y acceso a una gran 
          comunidad de estudiantes.</p>
        <button onclick="location.href='cursos.php';" type="submit" name="cursos" value="cursos">Ver todos los cursos</button>
      </div>
    </div>

    <hr>

    <div class="alumnos">
      <img class="alumni" src="img/chat_alumnos.png" alt="">
      <div class="info">
        <h2> Aprende junto a tus compañeros </h2>
        <p>Está demostrado que aprender en grupo es más eficiente y motivador. 
        El networking con tus compañeros de clase ayuda a que puedas tener nuevas 
        ideas y hacer mejores proyectos. </p>
      </div>  
      
    </div>

    <div class="profesores">
      <div>
        <img class="profe" src="img/chat_profe.png" alt="">
      </div>
      <div class="doble_caja">
        <img class="profesionales" src="img/profesionales.png" alt="">
        <img class="tutores" src="img/tutores.png" alt="">
      </div>
    </div>

    <div class="recomendaciones">
    <h2> Más de 200.000 estudiantes nos recomiendan en todo Europa </h2>
    <img class="reco" src="img/recomendaciones.png" alt="">

    </div>
EOS;
include 'includes/vistas/plantillas/plantilla.php';
?>