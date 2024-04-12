<?php
session_start();
// Conexión a la base de datos
require_once(dirname(__FILE__) . '/../../config.php');
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

// Obtener el término de búsqueda del parámetro de la solicitud
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

// Consulta SQL para obtener los cursos que coinciden con el término de búsqueda
$query = "SELECT * FROM Curso WHERE nombre_curso LIKE '%$searchTerm%'";

// Ejecutar la consulta
$resultado = $conn->query($query);

// Array para almacenar los resultados
$resultados = [];

// Verificar si la consulta fue exitosa
if ($resultado) {
    // Recorrer los resultados de la consulta
    while ($row = $resultado->fetch_assoc()) {
        // Agregar cada curso al array de resultados
        $curso = [
            "nombre" => $row["nombre_curso"],
            "precio" => $row["precio"] . " EUR", // Añadir el símbolo de la moneda
            "url" => "curso.php?nombre_curso=" . urlencode($row["nombre_curso"]) // Codificar el nombre del curso para la URL
        ];
        $resultados[] = $curso;
    }
}

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($resultados);
?>
