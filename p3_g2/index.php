<?php
require_once __DIR__.'/includes/config.php';
$tituloPagina = 'Pagina Principal';

$contenidoPrincipal = <<<EOS
  <div id="contenedor_index">
    <div class="rec1_index">
        <div class="texto_rec1_index"> 
          <h1> Cursos Online para transformar tu realidad en tiempo récord</h1>
          <p>Clases en línea y en vivo dictadas por referentes de la industria, 
            enfoque 100% práctico, mentorías personalizadas y acceso a una gran 
            comunidad de estudiantes.</p>
          <button onclick="location.href='cursos.php';" type="submit" name="cursos" value="cursos">Ver todos los cursos</button>
        </div>
    </div>

    <div class="rec2_index">
      <img id="img1_index" src="img/chat_alumnos.png" alt="">
      <div class="info_rec2_index">
        <h2> Aprende junto a tus compañeros </h2>
        <p>Está demostrado que aprender en grupo es más eficiente y motivador. 
        El networking con tus compañeros de clase ayuda a que puedas tener nuevas 
        ideas y hacer mejores proyectos. </p>
      </div>   
    </div>

    <div class="rec3_index">
      <div>
        <img id="img2_index" src="img/chat_profe.png" alt="">
      </div>
      <div class="doble_caja">
        <img id="img3_index" src="img/profesionales.png" alt="">
        <img id="img4_index" src="img/tutores.png" alt="">
      </div>
    </div>

    <div class="rec4_index">
      <h2> Más de 200.000 estudiantes nos recomiendan en todo Europa </h2>
      <img id="img5_index" src="img/recomendaciones.png" alt="">
    </div>
  </div>

EOS;
include 'includes/vistas/plantillas/plantilla.php';