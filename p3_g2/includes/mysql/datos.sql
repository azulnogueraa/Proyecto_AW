

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
VALUES ('Desarrollo Web', 'Aprende a crear aplicaciones web modernas con HTML, CSS, JavaScript y React.', '1', '2023-12-01', '30h', 'Intermedio', 'Tecnología', 'Conocimientos básicos de HTML y CSS', 80, 'Activo');

INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES ('Finanzas', 'Aprende a gestionar tus finanzas personales y planificar tu futuro financiero de manera eficaz.', '2', '2024-02-15', '15h', 'Principiante', 'Finanzas', 'No se requieren conocimientos previos', 50, 'Activo');

INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES ('Diseño Gráfico', 'Explora los fundamentos del diseño gráfico y domina Adobe Illustrator y Photoshop.', '1', '2024-03-10', '25h', 'Intermedio', 'Arte y Diseño', 'Conocimientos básicos de diseño', 70, 'Activo');

INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES ('Marketing Digital', 'Descubre estrategias avanzadas de marketing digital como SEO, SEM, redes sociales y analítica web.', '2', '2024-04-15', '30h', 'Avanzado', 'Negocios', 'Conocimientos básicos de marketing', 80, 'Activo');

INSERT INTO Curso (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso)
VALUES ('Blockchain', 'Aprende a desarrollar aplicaciones descentralizadas y contratos inteligentes con tecnologías blockchain.', '1', '2024-04-20', '40h', 'Avanzado', 'Tecnología', 'Conocimientos previos en programación', 90, 'Activo');
