<?php
// Conexión a la base de datos
require_once(dirname(__FILE__) . '/includes/src/config.php');
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

// Obtener el término de búsqueda del parámetro de la solicitud
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
$searchTermPrecio = isset($_GET['precio']) ? $_GET['precio'] : '';
// Consulta SQL para obtener los cursos que coinciden con el término de búsqueda
if( $searchTermPrecio !== ''){
    $query = "SELECT * FROM Curso WHERE nombre_curso LIKE '%$searchTerm%' and precio <= $searchTermPrecio";
}else{
    $query = "SELECT * FROM Curso WHERE nombre_curso LIKE '%$searchTerm%'";
}


// Ejecutar la consulta
$resultado = $conn->query($query);

// Verificar si la consulta fue exitosa
if ($resultado && $resultado->num_rows > 0) {
    // Generar HTML para los resultados
    while ($row = $resultado->fetch_assoc()) {
        echo '<div class="curso">';
        echo '<h2>' . $row["nombre_curso"] . '</h2>';
        echo '<p>Precio: ' . $row["precio"] . ' EUR</p>';
        echo '<a href="curso.php?nombre_curso=' . urlencode($row["nombre_curso"]) . '">Ver más</a>';
        echo '</div>';
    }
} else {
    // Si no se encontraron resultados, mostrar un mensaje
    echo '<p>No se encontraron resultados.</p>';
}
?>
