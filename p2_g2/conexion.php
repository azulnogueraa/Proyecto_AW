<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "learnique";
$password = "learnique";
$database = "learnique";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
