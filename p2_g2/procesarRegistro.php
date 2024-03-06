<?php
include 'conexion.php'; // Incluimos el archivo de conexión

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Realizar todas las validaciones necesarias y procesar el registro
    // ...

    // Verificar si el usuario o el correo electrónico ya están registrados en la base de datos
    $sql = "SELECT * FROM Usuarios WHERE nombre_usuario = '$usuario' OR email = '$email'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        // El usuario o el correo electrónico ya están registrados
        echo "No puedes usar este usuario o correo electrónico porque ya están registrados. Por favor, intenta con otro.";
    } else {
        // El usuario y el correo electrónico no están registrados, proceder con el registro

        // Consulta SQL para insertar un nuevo usuario
        $sql = "INSERT INTO usuarios (nombre_usuario, email, contraseña) VALUES ('$usuario', '$email', '$contraseña')";

        // Ejecutar la consulta de inserción
        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso";
        } else {
            echo "Error al registrar usuario: " . $conn->error;
        }
    }
}
?>
