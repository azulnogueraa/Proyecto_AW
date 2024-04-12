<?php
// Supongamos que estos son los cursos disponibles
$cursos = [
    ["nombre" => "Curso de Criptomonedas", "precio" => "50 EUR", "url" => "cripto.php"],
    ["nombre" => "Curso de Trading", "precio" => "40 EUR", "url" => "trading.php"],
    ["nombre" => "Curso de Blockchain", "precio" => "60 EUR", "url" => "blockchain.php"],
    ["nombre" => "Curso de Marketing", "precio" => "70 EUR", "url" => "marketing.php"],
    ["nombre" => "Curso de Marketing2", "precio" => "55 EUR", "url" => "curso.php?nombre_curso=Marketing"],
];

// Obtener el término de búsqueda del parámetro de la solicitud
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

// Filtrar los cursos que coincidan con el término de búsqueda
$resultados = array_filter($cursos, function($curso) use ($searchTerm) {
    // Convertir ambas cadenas a minúsculas para una comparación sin distinción entre mayúsculas y minúsculas
    $searchTermLower = strtolower($searchTerm);
    $cursoNameLower = strtolower($curso['nombre']);
    
    // Buscar el término de búsqueda en el nombre del curso
    return strpos($cursoNameLower, $searchTermLower) !== false;
});

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode(array_values($resultados)); // Convertir los resultados en un array indexado para asegurar la consistencia
?>
