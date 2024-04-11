

INSERT INTO Profesor (nombre_usuario, apellido, email, contrasena)
VALUES 
('javier', 'bravo', 'javier@profesor.com','$2y$10$B6nmOuYfQaEGvluC7fM1TOf7iKrdlQIpbGDara42EHRvHY8gIkvfa'),
('eva', 'ullan', 'eva@profesor.com','$2y$10$uBKn8TZ4Puko.BSqtUWksO/DQgfMYq/l89B8GzksGA4y4i4mvag6C');


INSERT INTO Estudiante (nombre_usuario, apellido, email, contrasena) 
VALUES 
('azul', 'noguera', 'azul@estudiante.com','$2y$10$Mtto4mZ.kRwCfMvPpDYDBOzco7ekMLJiKO35GZRFvBf6SuPRLbtAe'),
('rocio', 'gonzalez', 'rocio@estudiante.com','$2y$10$ZvpimRSX9aMCkdXg/bNzmuQCQpNdPdrgfcIV.HHaLWnmDiVXKuVTO'),
('patricio', 'guledjian', 'patricio@estudiante.com','$2y$10$Hu2/dTjTJZO4jpO1fe1LjOXXgYG0Wq.XeqEBESGJ1VVwK4u5aEURy'),
('gabriel', 'zamy', 'gabriel@estudiante.com','$2y$10$6I7tVnuOZr50QAzFhMJAvez6YXt9PLVJRDWNdCcOPghPLSziPi71q'),
('vincent', 'jansou', 'vincent@estudiante.com','$2y$10$iUNSZvgoCRoAetyIGqfC3u9fCH14CA0hLf5uNBojE/2caIrStop46');


INSERT INTO Administrador (nombre_usuario, apellido, email, contrasena)
VALUES 
('admin', 'admin', 'admin@admin.com','$2y$10$ZASX632WWFlPQmI6Xe1HjedIZGxeU4jEyxWEmuA5.nKAAHhHJ.Fr6');
GRANT ALL PRIVILEGES ON `learnique`.* TO 'admin'@'%';

INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES
('Marketing', 'descripcion del curso', '6','2024-01-01', '20h','Intermedio', 'Negocios', '', 60, 'Activo')