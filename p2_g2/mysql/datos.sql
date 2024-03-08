

INSERT INTO Profesor (id, nombre_usuario, apellido, email, contrasena)
VALUES 
('-2', 'javier', 'bravo', 'javier@profesor.com','javier1'),
('-1', 'eva', 'ullan', 'eva@profesor.com','eva12');


INSERT INTO Estudiante (id, nombre_usuario, apellido, email, contrasena) 
VALUES 
('-5','azul', 'noguera', 'azul@estudiante.com','azul1'),
('-4', 'rocio', 'gonzalez', 'rocio@estudiante.com','rocio1'),
('-3', 'patricio', 'guledjian', 'patricio@estudiante.com','patricio1'),
('-2', 'gabriel', 'zamy', 'gabriel@estudiante.com','gabriel1'),
('-1', 'vincent', 'jansou', 'vincent@estudiante.com','vincent1');


INSERT INTO Administrador (id, nombre_usuario, apellido, email, contrasena)
VALUES 
('-1', 'admin', 'admin', 'admin@admin.com','admin1');


INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES
('Marketing', 'descripcion del curso', '-1','2024-01-01', '20h','Intermedio', 'Negocios', '', 60, 'Activo')