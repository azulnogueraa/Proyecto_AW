create table if not exists Estudiante (
    id INT AUTO_INCREMENT,
    nombre_usuario varchar(100),
    apellido varchar(100),
    email varchar(100),
    contrasena varchar(255),
    primary key (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Profesor (
    id INT AUTO_INCREMENT,
    nombre_usuario varchar(100),
    apellido varchar(100),
    email varchar(100),
    contrasena varchar(255),
    experiencia varchar(255),
    primary key (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Administrador (
    id INT AUTO_INCREMENT,
    nombre_usuario varchar(100),
    apellido varchar(100),
    email varchar(100),
    contrasena varchar(255),
    primary key (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS Curso (
    nombre_curso VARCHAR(100) ,
    descripcion TEXT NOT NULL,
    profesor_id INT NOT NULL,
    fecha_creacion timestamp NOT NULL,
    duracion VARCHAR(50) NOT NULL,
    nivel_dificultad ENUM('Principiante', 'Intermedio', 'Avanzado') NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    requisitos_previos TEXT,
    precio DECIMAL(10, 2),
    estado_curso ENUM('Activo', 'Inactivo', 'En desarrollo') NOT NULL,
    primary key (nombre_curso),
    foreign key (profesor_id) references profesor(id)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Commentarios (
    num int ,
    u_id int,
    curso_id VARCHAR(100), 
    created_at timestamp,
    contenido VARCHAR(250),
    primary key(num),
    foreign key(u_id) references Estudiante(id)

)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Registrado (
    u_id int,
    curso_id VARCHAR(100),
    primary key (u_id, curso_id),
    foreign key (u_id) references Estudiante(id),
    foreign key (curso_id) references Curso(nombre_curso)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
