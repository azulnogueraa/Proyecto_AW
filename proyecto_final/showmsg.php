<?php
// Conexión a la base de datos
require_once(dirname(__FILE__) . '/includes/src/config.php');
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

$curso = $_GET['curso'];
$contenido = $_GET['q'];
$u_id = $_SESSION['id'];
$sender = $_SESSION['nombre'];


$newMensaje = es\ucm\fdi\aw\Mensaje::crea($curso, $u_id, $contenido);
$id = $newMensaje->getId();

// Consulta SQL para obtener los cursos que coinciden con el término de búsqueda
$query = sprintf("SELECT * FROM Mensaje WHERE id = %d", $id);

// Ejecutar la consulta
$resultado = $conn->query($query);

// Verificar si la consulta fue exitosa
if ($resultado && $resultado->num_rows > 0) {
    // Generar HTML para los resultados
    while ($row = $resultado->fetch_assoc()) {
        echo '<div class="box-cursos">';
        echo '<h2 class="nombre-cursos">' . $row["contenido"] . '</h2>';
        echo '<div class="precio-cursos"> ' . $sender . ' </div>';
        echo '</div>';
    }
} else {
    // Si no se encontraron resultados, mostrar un mensaje
    echo '<p>No se encontraron resultados.</p>';
}
?>