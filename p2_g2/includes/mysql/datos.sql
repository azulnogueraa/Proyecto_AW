

INSERT INTO Profesor (nombre_usuario, apellido, email, contrasena)
VALUES 
('javier', 'bravo', 'javier@profesor.com','javier1'),
('eva', 'ullan', 'eva@profesor.com','eva12');


INSERT INTO Estudiante (nombre_usuario, apellido, email, contrasena) 
VALUES 
('azul', 'noguera', 'azul@estudiante.com','azul1'),
('rocio', 'gonzalez', 'rocio@estudiante.com','rocio1'),
('patricio', 'guledjian', 'patricio@estudiante.com','patricio1'),
('gabriel', 'zamy', 'gabriel@estudiante.com','gabriel1'),
('vincent', 'jansou', 'vincent@estudiante.com','vincent1');


INSERT INTO Administrador (nombre_usuario, apellido, email, contrasena)
VALUES 
('admin', 'admin', 'admin@admin.com','admin1');


INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES
('Marketing', 'descripcion del curso', '6','2024-01-01', '20h','Intermedio', 'Negocios', '', 60, 'Activo')

INSERT INTO Mensaje (id, mensaje, created_at, user_id, tipo_usuario, nombre_curso)
VALUES
('1', 'Hola, les doy la bienvenida al curso de Blockchain', '2024-05-10 19:17:00','1', 'profesor','Blockchain')
('2', 'Hola, Buenas!', '2024-05-10 19:19:02','1', 'Estudiante','Blockchain')
('3', 'Que tal!', '2024-05-10 19:30:09','2', 'Estudiante','Blockchain')
