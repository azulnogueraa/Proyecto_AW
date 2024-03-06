DROP TABLE IF EXISTS `Usuarios`;
DROP TABLE IF EXISTS `Cursos`;

'''
nombre_usuario: El nombre de usuario único que actuará como clave única. 
nombre: El nombre del usuario.
apellido: El apellido del usuario.
email: El correo electrónico del usuario, que también se define como único.
contraseña: La contraseña del usuario, almacenada de manera segura.
rol: El rol del usuario en el sistema (Estudiante, Profesor o Administrador).
'''

USE Learnique;
CREATE TABLE IF NOT EXISTS Usuarios (
    nombre_usuario VARCHAR(100) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('Estudiante', 'Profesor', 'Administrador') NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

'''
nombre_curso: El nombre del curso, establecido como clave primaria.
descripcion: Una descripción detallada del curso.
profesor_id: El ID del usuario que es el profesor o instructor a cargo del curso.
fecha_creacion: La fecha en que el curso fue creado.
duracion: La duración estimada del curso.
nivel_dificultad: El nivel de dificultad del curso.
categoria: La categoría o área temática del curso.
requisitos_previos: Requisitos previos necesarios para tomar el curso.
precio: El precio del curso.
estado_curso: El estado actual del curso.
'''

CREATE TABLE IF NOT EXISTS Cursos (
    nombre_curso VARCHAR(100) PRIMARY KEY,
    descripcion TEXT NOT NULL,
    profesor_id INT NOT NULL,
    fecha_creacion DATE NOT NULL,
    duracion VARCHAR(50) NOT NULL,
    nivel_dificultad ENUM('Principiante', 'Intermedio', 'Avanzado') NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    requisitos_previos TEXT,
    precio DECIMAL(10, 2),
    estado_curso ENUM('Activo', 'Inactivo', 'En desarrollo') NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
