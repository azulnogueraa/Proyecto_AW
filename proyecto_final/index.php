<?php
require_once __DIR__.'/includes/src/config.php';

// Definir el título de la página
$tituloPagina = 'Pagina Principal';

// Usar rutas absolutas para las imágenes
$rutaImg1 = htmlspecialchars('img/chat_alumnos.png', ENT_QUOTES, 'UTF-8');
$rutaImg2 = htmlspecialchars('img/chat_profe.png', ENT_QUOTES, 'UTF-8');
$rutaImg3 = htmlspecialchars('img/profesionales.png', ENT_QUOTES, 'UTF-8');
$rutaImg4 = htmlspecialchars('img/tutores.png', ENT_QUOTES, 'UTF-8');
$rutaImg5 = htmlspecialchars('img/recomendaciones.png', ENT_QUOTES, 'UTF-8');

// Crear el contenido principal de la página
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
      <img id="img1_index" src="$rutaImg1" alt="Chat con Alumnos">
      <div class="info_rec2_index">
        <h2> Aprende junto a tus compañeros </h2>
        <p>Está demostrado que aprender en grupo es más eficiente y motivador. 
        El networking con tus compañeros de clase ayuda a que puedas tener nuevas 
        ideas y hacer mejores proyectos. </p>
      </div>   
    </div>

    <div class="rec3_index">
      <div>
        <img id="img2_index" src="$rutaImg2" alt="Chat con Profesores">
      </div>
      <div class="doble_caja">
        <img id="img3_index" src="$rutaImg3" alt="Profesionales">
        <img id="img4_index" src="$rutaImg4" alt="Tutores">
      </div>
    </div>

    <div class="rec4_index">
      <h2> Más de 200.000 estudiantes nos recomiendan en toda Europa </h2>
      <img id="img5_index" src="$rutaImg5" alt="Recomendaciones">
    </div>
  </div>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
