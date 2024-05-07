<?php

// Iniciar sesión para obtener variables de sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id']) || !isset($_SESSION['nombre'])) {
    echo '<p>No tienes permiso para acceder a esta página.</p>';
    exit();
}

// Incluir configuración y clases necesarias
require_once __DIR__ . '/includes/src/config.php';
$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

// Obtener parámetros GET y validar
$curso = htmlspecialchars($_GET['curso'] ?? '', ENT_QUOTES, 'UTF-8');
$contenido = htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8');
$u_id = $_SESSION['id'];
$sender = htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8');

// Verificar si los datos obligatorios están presentes
if ($curso && $contenido) {
    // Crear el nuevo mensaje
    $newMensaje = es\ucm\fdi\aw\Mensaje::crea($curso, $u_id, $contenido);
    $id = $newMensaje->getId();

    // Consulta SQL para obtener el mensaje recién creado
    $query = "SELECT * FROM Mensaje WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Mostrar el mensaje si se encuentra
    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '<div class="box-cursos">';
            echo '<h2 class="nombre-cursos">' . htmlspecialchars($row['contenido'], ENT_QUOTES, 'UTF-8') . '</h2>';
            echo '<div class="precio-cursos"> ' . $sender . ' </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No se encontraron resultados.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Faltan parámetros para crear el mensaje.</p>';
}
?>
