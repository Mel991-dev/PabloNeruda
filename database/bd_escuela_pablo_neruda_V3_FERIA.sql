-- SCRIPT CONSOLIDADO V3 (FERIA TECNOLOGICA)
-- Generado automáticamente

-- =========================================
-- ARCHIVO: bd_escuela_pablo_neruda.sql
-- =========================================

-- ============================================================================
-- SCRIPT SQL - SISTEMA DE GESTIÓN ACADÉMICA - CORREGIDO
-- ============================================================================

DROP DATABASE IF EXISTS escuela_pablo_neruda;

CREATE DATABASE escuela_pablo_neruda
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE escuela_pablo_neruda;

-- ============================================================================
-- TABLA 1: USUARIOS
-- ============================================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('Administrador', 'Rector', 'Coordinador', 'Profesor') NOT NULL,
    fk_profesor INT DEFAULT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME DEFAULT NULL,
    INDEX idx_rol (rol),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 2: PROFESORES
-- ============================================================================
CREATE TABLE profesores (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_documento ENUM('CC', 'TI', 'CE') NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(15) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    especialidad VARCHAR(100) DEFAULT NULL,
    fecha_ingreso DATE DEFAULT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
    INDEX idx_estado (estado),
    INDEX idx_nombre (nombre, apellido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 3: ESTUDIANTES (CORREGIDA)
-- Se eliminó la columna 'edad' generada para evitar el error 3763
-- ============================================================================
CREATE TABLE estudiantes (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    -- La edad se calculará en las VISTAS, no aquí.
    tipo_documento ENUM('RC', 'TI') NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    registro_civil VARCHAR(30) DEFAULT NULL,
    tarjeta_identidad VARCHAR(30) DEFAULT NULL,
    documento_pdf VARCHAR(255) DEFAULT NULL COMMENT 'Ruta del archivo PDF del documento',
    tiene_alergias BOOLEAN NOT NULL DEFAULT FALSE,
    descripcion_alergias VARCHAR(500) DEFAULT NULL,
    numero_hermanos INT DEFAULT 0,
    estado ENUM('Activo', 'Retirado', 'Graduado') NOT NULL DEFAULT 'Activo',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre, apellido),
    INDEX idx_estado (estado),
    INDEX idx_alergias (tiene_alergias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 4: ACUDIENTES
-- ============================================================================
CREATE TABLE acudientes (
    id_acudiente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'Pasaporte') NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(15) NOT NULL,
    telefono_secundario VARCHAR(15) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    direccion VARCHAR(200) DEFAULT NULL,
    ocupacion VARCHAR(100) DEFAULT NULL,
    INDEX idx_nombre (nombre, apellido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 5: ESTUDIANTE_ACUDIENTE
-- ============================================================================
CREATE TABLE estudiante_acudiente (
    id_estudiante_acudiente INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    fk_acudiente INT NOT NULL,
    parentesco ENUM('Padre', 'Madre', 'Abuelo', 'Abuela', 'Tío', 'Tía', 'Hermano', 'Otro') NOT NULL,
    es_acudiente_principal BOOLEAN NOT NULL DEFAULT FALSE,
    con_quien_vive BOOLEAN NOT NULL DEFAULT FALSE,
    autorizado_recoger BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY idx_est_acud (fk_estudiante, fk_acudiente),
    INDEX idx_estudiante (fk_estudiante),
    INDEX idx_acudiente (fk_acudiente),
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    FOREIGN KEY (fk_acudiente) REFERENCES acudientes(id_acudiente) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 6: CURSOS
-- ============================================================================
CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    grado ENUM('Preescolar', '1°', '2°', '3°', '4°', '5°') NOT NULL,
    seccion ENUM('A', 'B', 'C') NOT NULL,
    año_lectivo INT NOT NULL,
    jornada ENUM('Mañana', 'Tarde') NOT NULL,
    capacidad_maxima INT NOT NULL DEFAULT 35,
    director_grupo INT DEFAULT NULL,
    UNIQUE KEY idx_curso_unico (grado, seccion, año_lectivo, jornada),
    INDEX idx_año (año_lectivo),
    INDEX idx_jornada (jornada),
    FOREIGN KEY (director_grupo) REFERENCES profesores(id_profesor) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 7: MATRICULAS
-- ============================================================================
CREATE TABLE matriculas (
    id_matricula INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    fk_curso INT NOT NULL,
    año_lectivo INT NOT NULL,
    fecha_matricula DATE NOT NULL DEFAULT (CURRENT_DATE),
    estado ENUM('Activo', 'Retirado', 'Graduado') NOT NULL DEFAULT 'Activo',
    UNIQUE KEY idx_matricula_unica (fk_estudiante, año_lectivo),
    INDEX idx_curso (fk_curso),
    INDEX idx_estado (estado),
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    FOREIGN KEY (fk_curso) REFERENCES cursos(id_curso) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 8: MATERIAS
-- ============================================================================
CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    grado_aplicable ENUM('Preescolar', '1°', '2°', '3°', '4°', '5°', 'Todos') DEFAULT 'Todos',
    intensidad_horaria INT DEFAULT 2,
    descripcion TEXT DEFAULT NULL,
    INDEX idx_grado (grado_aplicable)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 9: PROFESOR_MATERIA_CURSO
-- ============================================================================
CREATE TABLE profesor_materia_curso (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    fk_profesor INT NOT NULL,
    fk_materia INT NOT NULL,
    fk_curso INT NOT NULL,
    año_lectivo INT NOT NULL,
    fecha_asignacion DATE NOT NULL DEFAULT (CURRENT_DATE),
    UNIQUE KEY idx_asignacion_unica (fk_profesor, fk_materia, fk_curso, año_lectivo),
    INDEX idx_profesor (fk_profesor),
    INDEX idx_materia (fk_materia),
    INDEX idx_curso (fk_curso),
    FOREIGN KEY (fk_profesor) REFERENCES profesores(id_profesor) ON DELETE CASCADE,
    FOREIGN KEY (fk_materia) REFERENCES materias(id_materia) ON DELETE RESTRICT,
    FOREIGN KEY (fk_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA 10: NOTAS
-- ============================================================================
CREATE TABLE notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    fk_matricula INT NOT NULL,
    fk_materia INT NOT NULL,
    fk_profesor INT NOT NULL,
    periodo INT NOT NULL CHECK (periodo BETWEEN 1 AND 4),
    nota_1 DECIMAL(3,2) DEFAULT NULL CHECK (nota_1 BETWEEN 0.00 AND 5.00),
    nota_2 DECIMAL(3,2) DEFAULT NULL CHECK (nota_2 BETWEEN 0.00 AND 5.00),
    nota_3 DECIMAL(3,2) DEFAULT NULL CHECK (nota_3 BETWEEN 0.00 AND 5.00),
    nota_4 DECIMAL(3,2) DEFAULT NULL CHECK (nota_4 BETWEEN 0.00 AND 5.00),
    nota_5 DECIMAL(3,2) DEFAULT NULL CHECK (nota_5 BETWEEN 0.00 AND 5.00),
    promedio_periodo DECIMAL(3,2) GENERATED ALWAYS AS (
        CASE 
            WHEN nota_1 IS NOT NULL AND nota_2 IS NOT NULL AND 
                 nota_3 IS NOT NULL AND nota_4 IS NOT NULL AND nota_5 IS NOT NULL
            THEN (nota_1 + nota_2 + nota_3 + nota_4 + nota_5) / 5
            ELSE NULL
        END
    ) STORED,
    estado VARCHAR(20) GENERATED ALWAYS AS (
        CASE 
            WHEN promedio_periodo >= 3.00 THEN 'Aprobado'
            WHEN promedio_periodo < 3.00 THEN 'Reprobado'
            ELSE NULL
        END
    ) STORED,
    observaciones TEXT DEFAULT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY idx_nota_unica (fk_matricula, fk_materia, periodo),
    INDEX idx_matricula (fk_matricula),
    INDEX idx_estado (estado),
    INDEX idx_periodo (periodo),
    FOREIGN KEY (fk_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE,
    FOREIGN KEY (fk_materia) REFERENCES materias(id_materia) ON DELETE RESTRICT,
    FOREIGN KEY (fk_profesor) REFERENCES profesores(id_profesor) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- FOREIGN KEYS ADICIONALES
-- ============================================================================
ALTER TABLE usuarios
ADD CONSTRAINT fk_usuario_profesor 
FOREIGN KEY (fk_profesor) REFERENCES profesores(id_profesor) ON DELETE SET NULL;

-- ============================================================================
-- VISTAS ÚTILES (CORREGIDAS)
-- ============================================================================

-- Vista: Información completa de estudiantes con acudientes
-- CORRECCIÓN: Se calcula la edad dinámicamente aquí
CREATE VIEW v_estudiantes_completo AS
SELECT 
    e.id_estudiante,
    e.nombre AS nombre_estudiante,
    e.apellido AS apellido_estudiante,
    e.numero_documento,
    TIMESTAMPDIFF(YEAR, e.fecha_nacimiento, CURDATE()) AS edad,
    e.tiene_alergias,
    e.descripcion_alergias,
    e.estado AS estado_estudiante,
    GROUP_CONCAT(
        CONCAT(a.nombre, ' ', a.apellido, ' (', ea.parentesco, ')')
        SEPARATOR ', '
    ) AS acudientes
FROM estudiantes e
LEFT JOIN estudiante_acudiente ea ON e.id_estudiante = ea.fk_estudiante
LEFT JOIN acudientes a ON ea.fk_acudiente = a.id_acudiente
GROUP BY e.id_estudiante;

-- Vista: Notas con información completa
CREATE VIEW v_notas_completo AS
SELECT 
    n.id_nota,
    CONCAT(e.nombre, ' ', e.apellido) AS estudiante,
    e.numero_documento,
    c.grado,
    c.seccion,
    m.nombre AS materia,
    CONCAT(p.nombre, ' ', p.apellido) AS profesor,
    n.periodo,
    n.nota_1,
    n.nota_2,
    n.nota_3,
    n.nota_4,
    n.nota_5,
    n.promedio_periodo,
    n.estado,
    n.fecha_registro
FROM notas n
INNER JOIN matriculas mat ON n.fk_matricula = mat.id_matricula
INNER JOIN estudiantes e ON mat.fk_estudiante = e.id_estudiante
INNER JOIN cursos c ON mat.fk_curso = c.id_curso
INNER JOIN materias m ON n.fk_materia = m.id_materia
INNER JOIN profesores p ON n.fk_profesor = p.id_profesor;

-- Vista: Estadísticas por curso
CREATE VIEW v_estadisticas_curso AS
SELECT 
    c.id_curso,
    c.grado,
    c.seccion,
    c.año_lectivo,
    c.jornada,
    COUNT(DISTINCT m.fk_estudiante) AS total_estudiantes,
    COUNT(DISTINCT CASE WHEN e.tiene_alergias = TRUE THEN m.fk_estudiante END) AS estudiantes_con_alergias
FROM cursos c
LEFT JOIN matriculas m ON c.id_curso = m.fk_curso AND m.estado = 'Activo'
LEFT JOIN estudiantes e ON m.fk_estudiante = e.id_estudiante
GROUP BY c.id_curso;

-- ============================================================================
-- DATOS INICIALES
-- ============================================================================

INSERT INTO materias (nombre, grado_aplicable, intensidad_horaria) VALUES
('Matemáticas', 'Todos', 5),
('Español', 'Todos', 5),
('Informática', 'Todos', 2),
('Inglés', 'Todos', 3),
('Religión', 'Todos', 2),
('Ética', 'Todos', 2),
('Biología', 'Todos', 3),
('Tecnología', 'Todos', 2),
('Artística', 'Todos', 2),
('Educación Física', 'Todos', 2),
('Sociales', 'Todos', 3);

INSERT INTO usuarios (username, password_hash, rol, estado) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Activo');

INSERT INTO cursos (grado, seccion, año_lectivo, jornada, capacidad_maxima) VALUES
('Preescolar', 'A', 2025, 'Mañana', 35),
('Preescolar', 'B', 2025, 'Tarde', 35),
('1°', 'A', 2025, 'Mañana', 35),
('1°', 'B', 2025, 'Mañana', 35),
('1°', 'C', 2025, 'Tarde', 35),
('2°', 'A', 2025, 'Mañana', 35),
('2°', 'B', 2025, 'Mañana', 35),
('2°', 'C', 2025, 'Tarde', 35),
('3°', 'A', 2025, 'Mañana', 35),
('3°', 'B', 2025, 'Mañana', 35),
('3°', 'C', 2025, 'Tarde', 35),
('4°', 'A', 2025, 'Mañana', 35),
('4°', 'B', 2025, 'Mañana', 35),
('4°', 'C', 2025, 'Tarde', 35),
('5°', 'A', 2025, 'Mañana', 35),
('5°', 'B', 2025, 'Mañana', 35),
('5°', 'C', 2025, 'Tarde', 35);

-- ============================================================================
-- TRIGGERS
-- ============================================================================

DELIMITER //
CREATE TRIGGER trg_validar_capacidad_curso
BEFORE INSERT ON matriculas
FOR EACH ROW
BEGIN
    DECLARE estudiantes_actuales INT;
    DECLARE capacidad_max INT;
    
    SELECT COUNT(*) INTO estudiantes_actuales
    FROM matriculas
    WHERE fk_curso = NEW.fk_curso 
    AND año_lectivo = NEW.año_lectivo
    AND estado = 'Activo';
    
    SELECT capacidad_maxima INTO capacidad_max
    FROM cursos
    WHERE id_curso = NEW.fk_curso;
    
    IF estudiantes_actuales >= capacidad_max THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El curso ha alcanzado su capacidad máxima';
    END IF;
END//
DELIMITER ;

-- ============================================================================
-- STORED PROCEDURES (CORREGIDOS)
-- ============================================================================

DELIMITER //
CREATE PROCEDURE sp_reporte_estudiante(IN p_id_estudiante INT, IN p_año_lectivo INT)
BEGIN
    -- Información básica del estudiante
    -- CORRECCIÓN: Cálculo dinámico de edad
    SELECT 
        e.id_estudiante,
        CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
        e.numero_documento,
        e.fecha_nacimiento,
        TIMESTAMPDIFF(YEAR, e.fecha_nacimiento, CURDATE()) AS edad,
        c.grado,
        c.seccion,
        c.jornada,
        e.tiene_alergias,
        e.descripcion_alergias
    FROM estudiantes e
    INNER JOIN matriculas m ON e.id_estudiante = m.fk_estudiante
    INNER JOIN cursos c ON m.fk_curso = c.id_curso
    WHERE e.id_estudiante = p_id_estudiante
    AND m.año_lectivo = p_año_lectivo;
    
    -- Acudientes
    SELECT 
        CONCAT(a.nombre, ' ', a.apellido) AS acudiente,
        ea.parentesco,
        a.telefono,
        a.email,
        ea.es_acudiente_principal
    FROM estudiante_acudiente ea
    INNER JOIN acudientes a ON ea.fk_acudiente = a.id_acudiente
    WHERE ea.fk_estudiante = p_id_estudiante;
    
    -- Notas por período
    SELECT 
        m.nombre AS materia,
        n.periodo,
        n.nota_1,
        n.nota_2,
        n.nota_3,
        n.nota_4,
        n.nota_5,
        n.promedio_periodo,
        n.estado
    FROM notas n
    INNER JOIN materias m ON n.fk_materia = m.id_materia
    INNER JOIN matriculas mat ON n.fk_matricula = mat.id_matricula
    WHERE mat.fk_estudiante = p_id_estudiante
    AND mat.año_lectivo = p_año_lectivo
    ORDER BY n.periodo, m.nombre;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_estadisticas_curso(IN p_id_curso INT, IN p_periodo INT)
BEGIN
    SELECT 
        m.nombre AS materia,
        COUNT(n.id_nota) AS total_estudiantes,
        AVG(n.promedio_periodo) AS promedio_general,
        SUM(CASE WHEN n.estado = 'Aprobado' THEN 1 ELSE 0 END) AS aprobados,
        SUM(CASE WHEN n.estado = 'Reprobado' THEN 1 ELSE 0 END) AS reprobados,
        ROUND(SUM(CASE WHEN n.estado = 'Aprobado' THEN 1 ELSE 0 END) * 100.0 / COUNT(n.id_nota), 2) AS porcentaje_aprobacion
    FROM notas n
    INNER JOIN materias m ON n.fk_materia = m.id_materia
    INNER JOIN matriculas mat ON n.fk_matricula = mat.id_matricula
    WHERE mat.fk_curso = p_id_curso
    AND n.periodo = p_periodo
    AND n.promedio_periodo IS NOT NULL
    GROUP BY m.id_materia
    ORDER BY m.nombre;
END//
DELIMITER ;

-- ============================================================================
-- ÍNDICES ADICIONALES
-- ============================================================================

CREATE INDEX idx_estudiante_nombre_completo ON estudiantes(nombre, apellido, estado);
CREATE INDEX idx_profesor_nombre_completo ON profesores(nombre, apellido, estado);
CREATE INDEX idx_notas_busqueda ON notas(fk_matricula, periodo, estado);

-- ============================================================================
-- COMENTARIOS
-- ============================================================================

ALTER TABLE usuarios COMMENT = 'Almacena credenciales y roles para acceso al sistema';
ALTER TABLE profesores COMMENT = 'Información personal y profesional de los docentes';
ALTER TABLE estudiantes COMMENT = 'Información completa de estudiantes incluyendo datos médicos';
ALTER TABLE acudientes COMMENT = 'Datos de padres y tutores responsables';
ALTER TABLE cursos COMMENT = 'Definición de grados, secciones y jornadas';
ALTER TABLE matriculas COMMENT = 'Registro de inscripción de estudiantes en cursos';
ALTER TABLE materias COMMENT = 'Catálogo de asignaturas del plan de estudios';
ALTER TABLE notas COMMENT = 'Calificaciones con cálculo automático de promedios';

-- ============================================================================
-- VERIFICACIÓN
-- ============================================================================

SELECT 
    TABLE_NAME AS 'Tabla',
    TABLE_ROWS AS 'Filas',
    AUTO_INCREMENT AS 'Próximo ID',
    CREATE_TIME AS 'Fecha Creación'
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'escuela_pablo_neruda'
AND TABLE_TYPE = 'BASE TABLE'
ORDER BY TABLE_NAME;

-- =========================================
-- ARCHIVO: 01_expansion_datos_v2.sql
-- =========================================

-- ============================================================================
-- MIGRACIÓN V2.0 - ITERACIÓN 1: EXPANSIÓN DE DATOS
-- Auto: Arquitecto de Software
-- Fecha: 2025-02-05
-- Descripción: Creación de tablas satélite para información detallada del estudiante
-- ============================================================================

USE escuela_pablo_neruda;

-- 1. TABLA: INFORMACIÓN FAMILIAR (Padres Biológicos)
-- Registro puramente informativo, separado del rol "Acudiente" (que es el legal)
CREATE TABLE IF NOT EXISTS familiares (
    id_familiar INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'Pasaporte') DEFAULT 'CC',
    numero_documento VARCHAR(20) DEFAULT NULL, -- Puede ser nulo si no se tiene el dato
    ocupacion VARCHAR(100) DEFAULT NULL,
    empresa VARCHAR(100) DEFAULT NULL,
    nivel_educativo ENUM('Primaria', 'Bachiller', 'Técnico', 'Tecnólogo', 'Profesional', 'Especialista', 'Doctorado', 'Ninguno', 'Otro') DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    direccion VARCHAR(200) DEFAULT NULL,
    barrio VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    vive_con_estudiante BOOLEAN DEFAULT FALSE,
    INDEX idx_doc (numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABLA: INFORMACIÓN DE SALUD DETALLADA
CREATE TABLE IF NOT EXISTS estudiantes_info_salud (
    id_info_salud INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    eps VARCHAR(100) DEFAULT NULL,
    tipo_sangre ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') DEFAULT NULL,
    limitaciones_fisicas TEXT DEFAULT NULL, -- Motriz, etc.
    limitaciones_sensoriales TEXT DEFAULT NULL, -- Auditiva, Visual
    medicamentos_permanentes TEXT DEFAULT NULL,
    vacunas_completas BOOLEAN DEFAULT FALSE,
    toma_medicamentos BOOLEAN DEFAULT FALSE,
    alergico_a TEXT DEFAULT NULL, -- Reemplaza o complementa el campo simple de estudiantes
    dificultad_salud TEXT DEFAULT NULL,
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABLA: INFORMACIÓN SOCIOECONÓMICA
CREATE TABLE IF NOT EXISTS estudiantes_socioeconomico (
    id_socioeconomico INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    sisben_nivel VARCHAR(10) DEFAULT NULL,
    estrato INT DEFAULT NULL,
    barrio VARCHAR(100) DEFAULT NULL,
    sector VARCHAR(100) DEFAULT NULL, -- Urbano / Rural
    tipo_vivienda ENUM('Propia', 'Arriendo', 'Familiar', 'Otra') DEFAULT NULL,
    tiene_internet BOOLEAN DEFAULT FALSE,
    servicios_publicos_completo BOOLEAN DEFAULT TRUE,
    victima_conflicto BOOLEAN DEFAULT FALSE,
    victima_conflicto_detalle TEXT DEFAULT NULL,
    grupo_etnico ENUM('Negritudes', 'Indígena', 'Raizal', 'Palenquero', 'Gitano', 'Mestizo', 'Blanco', 'Otro', 'Ninguno') DEFAULT 'Ninguno',
    resguardo_indigena VARCHAR(100) DEFAULT NULL,
    familias_en_accion BOOLEAN DEFAULT FALSE,
    poblacion_desplazada BOOLEAN DEFAULT FALSE,
    lugar_desplazamiento VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABLA: ANTECEDENTES ACADÉMICOS
CREATE TABLE IF NOT EXISTS antecedentes_academicos (
    id_antecedente INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    nivel_educativo VARCHAR(50) DEFAULT NULL, -- Preescolar, Primaria...
    institucion VARCHAR(150) DEFAULT NULL,
    años_cursados VARCHAR(50) DEFAULT NULL,
    motivo_retiro TEXT DEFAULT NULL,
    observaciones TEXT DEFAULT NULL, -- Rendimiento, fortalezas, dificultades
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ACTUALIZACIÓN TABLA ESTUDIANTES (Vínculos a Padres)
-- Agregamos llaves foráneas opcionales a la tabla familiares
ALTER TABLE estudiantes ADD COLUMN fk_padre INT DEFAULT NULL;
ALTER TABLE estudiantes ADD COLUMN fk_madre INT DEFAULT NULL;
ALTER TABLE estudiantes ADD COLUMN lugar_nacimiento VARCHAR(100) DEFAULT NULL;
ALTER TABLE estudiantes ADD COLUMN procedencia_institucion VARCHAR(100) DEFAULT NULL; -- Para "Nuevo/Antiguo/Procede"

ALTER TABLE estudiantes 
ADD CONSTRAINT fk_est_padre FOREIGN KEY (fk_padre) REFERENCES familiares(id_familiar) ON DELETE SET NULL;

ALTER TABLE estudiantes 
ADD CONSTRAINT fk_est_madre FOREIGN KEY (fk_madre) REFERENCES familiares(id_familiar) ON DELETE SET NULL;


-- =========================================
-- ARCHIVO: 02_modulo_orientacion.sql
-- =========================================

-- ============================================================================
-- MIGRACIÓN V2.1: MÓDULO DE ORIENTACIÓN Y ALERTAS TEMPRANAS
-- ============================================================================

USE escuela_pablo_neruda;

-- 1. Agregar el rol 'Orientador' a la tabla de usuarios
ALTER TABLE usuarios 
MODIFY COLUMN rol ENUM('Administrador', 'Rector', 'Coordinador', 'Profesor', 'Orientador') NOT NULL;

-- 2. Tabla para seguimiento y casos de orientación
CREATE TABLE IF NOT EXISTS seguimientos_orientacion (
    id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,
    fk_estudiante INT NOT NULL,
    fk_usuario_orientador INT NOT NULL,
    fecha_seguimiento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo_intervencion ENUM('Académica', 'Convivencia', 'Socioeconómica', 'Salud', 'Psicológica') NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    compromisos TEXT,
    remitido_a VARCHAR(100) DEFAULT NULL COMMENT 'Entidad externa si aplica (EPS, ICBF, etc)',
    estado ENUM('Abierto', 'En Proceso', 'Cerrado') NOT NULL DEFAULT 'Abierto',
    fecha_cierre DATETIME DEFAULT NULL,
    
    FOREIGN KEY (fk_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    FOREIGN KEY (fk_usuario_orientador) REFERENCES usuarios(id_usuario),
    INDEX idx_estudiante (fk_estudiante),
    INDEX idx_tipo (tipo_intervencion),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Vista para detectar Estudiantes en Riesgo Académico
-- Se consideran en riesgo si tienen un promedio < 3.0 o al menos 1 materia perdida
CREATE OR REPLACE VIEW v_estudiantes_riesgo_academico AS
SELECT 
    e.id_estudiante,
    CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
    CONCAT(c.grado, ' - ', c.seccion) AS nombre_curso,
    COUNT(DISTINCT n.fk_materia) AS materias_evaluadas,
    SUM(CASE WHEN n.promedio_periodo < 3.0 THEN 1 ELSE 0 END) AS materias_perdidas,
    ROUND(AVG(n.promedio_periodo), 2) AS promedio_general
FROM estudiantes e
JOIN matriculas m ON e.id_estudiante = m.fk_estudiante AND m.estado = 'Activo'
JOIN cursos c ON m.fk_curso = c.id_curso
LEFT JOIN notas n ON m.id_matricula = n.fk_matricula
WHERE e.estado = 'Activo'
GROUP BY e.id_estudiante, e.nombre, e.apellido, c.grado, c.seccion
HAVING materias_perdidas >= 1 OR promedio_general < 3.0;

-- 4. Vista para detectar Estudiantes en Situación de Vulnerabilidad
-- Identifica estudiantes víctimas, desplazados o con limitaciones físicas/sensoriales
CREATE OR REPLACE VIEW v_estudiantes_vulnerabilidad AS
SELECT 
    e.id_estudiante,
    CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
    CONCAT(c.grado, ' - ', c.seccion) AS nombre_curso,
    CASE WHEN s.victima_conflicto = 1 THEN 'Víctima' ELSE NULL END AS es_victima,
    CASE WHEN s.poblacion_desplazada = 1 THEN 'Desplazado' ELSE NULL END AS es_desplazado,
    CASE WHEN s.servicios_publicos_completo = 0 THEN 'Sin Servicios' ELSE NULL END AS falta_servicios,
    h.limitaciones_fisicas,
    h.limitaciones_sensoriales
FROM estudiantes e
JOIN matriculas m ON e.id_estudiante = m.fk_estudiante AND m.estado = 'Activo'
JOIN cursos c ON m.fk_curso = c.id_curso
LEFT JOIN estudiantes_socioeconomico s ON e.id_estudiante = s.fk_estudiante
LEFT JOIN estudiantes_info_salud h ON e.id_estudiante = h.fk_estudiante
WHERE e.estado = 'Activo' AND (
    s.victima_conflicto = 1 OR 
    s.poblacion_desplazada = 1 OR 
    s.servicios_publicos_completo = 0 OR 
    h.limitaciones_fisicas IS NOT NULL OR 
    h.limitaciones_sensoriales IS NOT NULL
);


-- =========================================
-- ARCHIVO: script_notificaciones.sql
-- =========================================

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario_origen` int(11) DEFAULT NULL COMMENT 'Usuario que originó la alerta, ej: Profesor',
  `fk_usuario_destino` int(11) DEFAULT NULL COMMENT 'Destinatario específico, si es null va a todos los del rol',
  `rol_destino` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rol al que va dirigido, ej: Orientador',
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ej: Riesgo Académico, Falta Asistencia',
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `enlace` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL para ver más detalles',
  `leida` tinyint(1) DEFAULT '0',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `fk_notif_origen` (`fk_usuario_origen`),
  KEY `fk_notif_destino` (`fk_usuario_destino`),
  CONSTRAINT `fk_notif_origen` FOREIGN KEY (`fk_usuario_origen`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_notif_destino` FOREIGN KEY (`fk_usuario_destino`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


