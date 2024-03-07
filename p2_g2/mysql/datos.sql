TRUNCATE TABLE `Usuarios`;
TRUNCATE TABLE `Cursos`;

INSERT INTO Usuarios (nombre_usuario, nombre, apellido, email, contraseña, rol) 
VALUES 
('usuario1', 'Rocio', 'Gonzalez', 'rogonz@user.com', 'contraseña1', 'Estudiante'),
('profesor1', 'Juan', 'Perez', 'juanperez@profesor.com', 'contraseña2', 'Profesor'),
('admin1', 'Julian', 'Alvarez', 'julianalv@admin.com', 'contraseña3', 'Administrador');

INSERT INTO Cursos (nombre_curso, descripcion, profesor_id, fecha_creacion, duracion, nivel_dificultad, categoria, requisitos_previos, precio, estado_curso) 
VALUES 
('Marketing', 
 'Curso completo sobre estrategias de marketing digital, incluyendo SEO, SEM, redes sociales, email marketing, y más.',
 2, 
 CURDATE(), 
 '3 meses', 
 'Intermedio', 
 'Marketing', 
 'Conocimientos básicos de marketing son útiles pero no requeridos.', 
 99.99, 
 'Activo');

insert into Usuario () values ();
insert into Commentarios () values();
insert into Estudiante() values ();
insert into Profesor() values ();
insert into Registrado() values();

