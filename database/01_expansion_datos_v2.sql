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
