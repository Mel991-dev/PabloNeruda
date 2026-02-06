-- ============================================================================
-- SCRIPT DE DATOS INICIALES (SEED)
-- Sistema Escuela Pablo Neruda
-- EJECUTAR DESPUÉS DE bd_escuela_pablo_neruda.sql
-- ============================================================================

USE escuela_pablo_neruda;

-- ============================================================================
-- INSERTAR PROFESORES DE EJEMPLO
-- ============================================================================
INSERT INTO profesores (nombre, apellido, tipo_documento, numero_documento, telefono, email, especialidad, fecha_ingreso, estado) VALUES
('Juan', 'Pérez', 'CC', '1234567890', '3001234567', 'juan.perez@escuela.edu.co', 'Matemáticas', '2024-01-15', 'Activo'),
('María', 'García', 'CC', '0987654321', '3009876543', 'maria.garcia@escuela.edu.co', 'Español', '2024-01-15', 'Activo'),
('Carlos', 'López', 'CC', '1122334455', '3005556677', 'carlos.lopez@escuela.edu.co', 'Ciencias', '2024-02-01', 'Activo');

-- ============================================================================
-- INSERTAR USUARIOS POR ROL
-- Contraseña para todos: password123 (hash bcrypt con cost 12)
-- ============================================================================

-- Usuario Administrador
INSERT INTO usuarios (username, password_hash, rol, fk_profesor, estado, fecha_creacion) VALUES
('admin', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY1w.1UovqRvK.S', 'Administrador', NULL, 'Activo', NOW());

-- Usuario Rector
INSERT INTO usuarios (username, password_hash, rol, fk_profesor, estado, fecha_creacion) VALUES
('rector', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY1w.1UovqRvK.S', 'Rector', NULL, 'Activo', NOW());

-- Usuario Coordinador
INSERT INTO usuarios (username, password_hash, rol, fk_profesor, estado, fecha_creacion) VALUES
('coordinador', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY1w.1UovqRvK.S', 'Coordinador', NULL, 'Activo', NOW());

-- Usuario Profesor (vinculado al primer profesor)
INSERT INTO usuarios (username, password_hash, rol, fk_profesor, estado, fecha_creacion) VALUES
('profesor', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY1w.1UovqRvK.S', 'Profesor', 1, 'Activo', NOW());

-- ============================================================================
-- INSERTAR CURSOS PARA AÑO 2026
-- ============================================================================
INSERT INTO cursos (grado, seccion, año_lectivo, jornada, capacidad_maxima, director_grupo) VALUES
('1°', 'A', 2026, 'Mañana', 35, 1),
('2°', 'A', 2026, 'Mañana', 35, 2),
('3°', 'A', 2026, 'Mañana', 35, 3),
('4°', 'A', 2026, 'Mañana', 35, NULL),
('5°', 'A', 2026, 'Mañana', 35, NULL);

-- ============================================================================
-- INSERTAR MATERIAS (Ya están en el script principal, pero agregamos más)
-- ============================================================================
-- Las materias ya se insertan en bd_escuela_pablo_neruda.sql

-- ============================================================================
-- ASIGNAR PROFESORES A MATERIAS Y CURSOS
-- ============================================================================
INSERT INTO profesor_materia_curso (fk_profesor, fk_materia, fk_curso, año_lectivo, fecha_asignacion) VALUES
(1, 1, 1, 2026, CURRENT_DATE), -- Juan Pérez enseña Matemáticas en 1° A
(2, 2, 1, 2026, CURRENT_DATE), -- María García enseña Español en 1° A
(3, 7, 1, 2026, CURRENT_DATE), -- Carlos López enseña Biología en 1° A
(1, 1, 2, 2026, CURRENT_DATE), -- Juan Pérez enseña Matemáticas en 2° A
(2, 2, 2, 2026, CURRENT_DATE); -- María García enseña Español en 2° A

-- ============================================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- ============================================================================

SELECT '=== PROFESORES CREADOS ===' AS '';
SELECT id_profesor, nombre, apellido, numero_documento, email, especialidad, estado FROM profesores;

SELECT '=== USUARIOS CREADOS ===' AS '';
SELECT id_usuario, username, rol, fk_profesor, estado FROM usuarios;

SELECT '=== CURSOS CREADOS ===' AS '';
SELECT id_curso, grado, seccion, año_lectivo, jornada, capacidad_maxima, director_grupo FROM cursos;

SELECT '=== ASIGNACIONES PROFESOR-MATERIA-CURSO ===' AS '';
SELECT 
    pmc.id_asignacion,
    CONCAT(p.nombre, ' ', p.apellido) AS profesor,
    m.nombre AS materia,
    CONCAT(c.grado, ' ', c.seccion) AS curso,
    pmc.año_lectivo
FROM profesor_materia_curso pmc
INNER JOIN profesores p ON pmc.fk_profesor = p.id_profesor
INNER JOIN materias m ON pmc.fk_materia = m.id_materia
INNER JOIN cursos c ON pmc.fk_curso = c.id_curso;

-- ============================================================================
-- CREDENCIALES DE ACCESO
-- ============================================================================
/*
CREDENCIALES PARA PRUEBAS:
==========================

Administrador:
- Usuario: admin
- Contraseña: password123

Rector:
- Usuario: rector
- Contraseña: password123

Coordinador:
- Usuario: coordinador
- Contraseña: password123

Profesor:
- Usuario: profesor
- Contraseña: password123

⚠️ IMPORTANTE: Cambiar estas contraseñas en producción
*/
