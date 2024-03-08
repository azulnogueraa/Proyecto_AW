<?php
$users = [
    ['javier', 'bravo', 'javier@profesor.com', 'javier1'],
    ['eva', 'ullan', 'eva@profesor.com', 'eva12'],
    ['azul', 'noguera', 'azul@estudiante.com','azul1'],
    ['rocio', 'gonzalez', 'rocio@estudiante.com','rocio1'],
    ['patricio', 'guledjian', 'patricio@estudiante.com','patricio1'],
    ['gabriel', 'zamy', 'gabriel@estudiante.com','gabriel1'],
    ['vincent', 'jansou', 'vincent@estudiante.com','vincent1'],
    ['admin', 'admin', 'admin@admin.com','admin1']
];

$sqlContent = "";

foreach ($users as $user) {
    // Hashear la contraseña
    $hashedPassword = password_hash($user[3], PASSWORD_DEFAULT);
    
    // Crear la instrucción SQL
   $sqlContent .= "INSERT INTO Profesor (nombre_usuario, apellido, email, contrasena) VALUES ('{$user[0]}', '{$user[1]}', '{$hashedPassword}');\n";
   $sqlContent .= "INSERT INTO Estudiante (nombre_usuario, apellido, email, contrasena) VALUES ('{$user[2]}', '{$user[3]}', '{$user[4]}','{$user[5]}','{$user[6]}', '{$hashedPassword}');\n";
   $sqlContent .= "INSERT INTO Administrador (nombre_usuario, apellido, email, contrasena) VALUES ('{$user[7]}', '{$hashedPassword}');\n";

}

// Guardar la instrucción SQL en un archivo
file_put_contents('insert_users.sql', $sqlContent);
?>
