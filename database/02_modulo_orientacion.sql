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
