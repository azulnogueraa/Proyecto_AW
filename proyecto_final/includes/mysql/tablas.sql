create table if not exists Estudiante (
    id INT AUTO_INCREMENT,
    nombre_usuario varchar(100),
    apellido varchar(100),
    email varchar(100),
    contrasena varchar(255),
    primary key (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS Profesor (
    id INT AUTO_INCREMENT,
    nombre_usuario VARCHAR(100),
    apellido VARCHAR(100),
    email VARCHAR(100),
    contrasena VARCHAR(255),
    experiencia VARCHAR(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Administrador (
    id INT AUTO_INCREMENT,
    nombre_usuario varchar(100),
    apellido varchar(100),
    email varchar(100),
    contrasena varchar(255),
    primary key (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS Curso (
    nombre_curso VARCHAR(100),
    descripcion TEXT NOT NULL,
    profesor_id INT,
    fecha_creacion TIMESTAMP NOT NULL,
    duracion VARCHAR(50) NOT NULL,
    nivel_dificultad ENUM('Principiante', 'Intermedio', 'Avanzado') NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    requisitos_previos TEXT,
    precio DECIMAL(10, 2),
    estado_curso ENUM('Activo', 'Inactivo', 'En desarrollo') NOT NULL,
    PRIMARY KEY (nombre_curso),
    FOREIGN KEY (profesor_id) REFERENCES Profesor(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


create table if not exists Mensaje (
    id int AUTO_INCREMENT,
    mensaje text NOT NULL,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id int NOT NULL,
    tipo_usuario ENUM('Administrador', 'Estudiante', 'Profesor') NOT NULL,
    nombre_curso VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (nombre_curso) REFERENCES Curso(nombre_curso)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table if not exists Registrado (
    u_id int,
    curso_id VARCHAR(100),
    primary key (u_id, curso_id),
    foreign key (u_id) references Estudiante(id),
    foreign key (curso_id) references Curso(nombre_curso)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;