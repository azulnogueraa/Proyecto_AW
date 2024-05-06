<?php
// Conexión a la base de datos
require_once(dirname(__FILE__) . '/includes/src/config.php');
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

// Obtener los términos de búsqueda del parámetro de la solicitud
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
$searchTermPrecio = isset($_GET['precio']) ? $_GET['precio'] : '';

$query = '';
$params = [];
$types = '';

// Definir la consulta en función de si se proporciona un límite de precio
if ($searchTermPrecio !== '') {
    $query = "SELECT * FROM Curso WHERE nombre_curso LIKE ? AND precio <= ?";
    $types = 'si';
    $params[] = "%$searchTerm%";
    $params[] = $searchTermPrecio;
} else {
    $query = "SELECT * FROM Curso WHERE nombre_curso LIKE ?";
    $types = 's';
    $params[] = "%$searchTerm%";
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($query);
if ($stmt === false) {
    error_log("Error preparando consulta: {$conn->error}");
    echo '<p>Hubo un problema al realizar la búsqueda.</p>';
    exit();
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si la consulta fue exitosa y mostrar los resultados
if ($resultado && $resultado->num_rows > 0) {
    // Generar HTML para los resultados
    while ($row = $resultado->fetch_assoc()) {
        echo '<div class="curso">';
        echo '<h2>' . htmlspecialchars($row["nombre_curso"], ENT_QUOTES, 'UTF-8') . '</h2>';
        echo '<p>Precio: ' . htmlspecialchars($row["precio"], ENT_QUOTES, 'UTF-8') . ' EUR</p>';
        echo '<a href="curso.php?nombre_curso=' . urlencode($row["nombre_curso"]) . '">Ver más</a>';
        echo '</div>';
    }
} else {
    // Si no se encontraron resultados, mostrar un mensaje
    echo '<p>No se encontraron resultados.</p>';
}

// Cerrar el statement
$stmt->close();
