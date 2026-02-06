# 📋 Cambios Detectados y Corregidos en seed_usuarios.sql

## Resumen de Cambios en la Base de Datos

### **Tabla: profesores**
- ❌ **Eliminado**: `cedula` (VARCHAR)
- ✅ **Agregado**: `tipo_documento` (ENUM: 'CC', 'TI', 'CE')
- ✅ **Agregado**: `numero_documento` (VARCHAR(20) UNIQUE)
- ✅ **Agregado**: `fecha_ingreso` (DATE)

### **Tabla: estudiantes**
- ❌ **Eliminado**: `edad` (columna generada automáticamente)
- 📝 **Nota**: La edad ahora se calcula en las vistas (v_estudiantes_completo)

### **Tabla: cursos**
- ❌ **Eliminado**: `nombre_curso` (VARCHAR)
- ❌ **Eliminado**: `estado` (ENUM)
- ✅ **Agregado**: `año_lectivo` (INT)
- ✅ **Agregado**: `director_grupo` (INT FK a profesores)
- 📝 **Cambio**: Los grados ahora usan símbolos: '1°', '2°', '3°', '4°', '5°'

### **Tabla: materias**
- ❌ **Eliminado**: `nombre_materia`
- ❌ **Eliminado**: `estado`
- ✅ **Ahora**: `nombre` (directamente)
- ✅ **Agregado**: `grado_aplicable` (ENUM)
- ✅ **Agregado**: `intensidad_horaria` (INT)
- 📝 **Nota**: Las materias ya se insertan en bd_escuela_pablo_neruda.sql

### **Tabla: profesor_materia_curso**
- ❌ **Eliminado**: `periodo_academico` (VARCHAR ejemplo: '2026-1')
- ✅ **Agregado**: `año_lectivo` (INT ejemplo: 2026)
- ✅ **Agregado**: `fecha_asignacion` (DATE)

---

## ✅ Cambios Aplicados en seed_usuarios.sql

### 1. **INSERT profesores** - Corregido ✓
```sql
-- ANTES (INCORRECTO):
INSERT INTO profesores (nombre, apellido, cedula, telefono, email, especialidad, estado)

-- AHORA (CORRECTO):
INSERT INTO profesores (nombre, apellido, tipo_documento, numero_documento, telefono, email, especialidad, fecha_ingreso, estado)
```

### 2. **INSERT cursos** - Corregido ✓
```sql
-- ANTES (INCORRECTO):
INSERT INTO cursos (nombre_curso, grado, seccion, jornada, capacidad_maxima, estado)
VALUES ('Primero A', 'Primero', 'A', ...)

-- AHORA (CORRECTO):
INSERT INTO cursos (grado, seccion, año_lectivo, jornada, capacidad_maxima, director_grupo)
VALUES ('1°', 'A', 2026, 'Mañana', 35, 1)
```

### 3. **INSERT materias** - Eliminado ✓
```sql
-- YA NO SE INSERTAN AQUÍ
-- Las materias ya están en bd_escuela_pablo_neruda.sql líneas 303-314
```

### 4. **INSERT profesor_materia_curso** - Corregido ✓
```sql
-- ANTES (INCORRECTO):
INSERT INTO profesor_materia_curso (fk_profesor, fk_materia, fk_curso, periodo_academico)
VALUES (1, 1, 1, '2026-1')

-- AHORA (CORRECTO):
INSERT INTO profesor_materia_curso (fk_profesor, fk_materia, fk_curso, año_lectivo, fecha_asignacion)
VALUES (1, 1, 1, 2026, CURRENT_DATE)
```

### 5. **Queries de Verificación** - Mejoradas ✓
Ahora incluyen:
- Encabezados claros
- JOINs para mostrar datos relacionados
- Columnas específicas (no SELECT *)

---

## 🎯 Listo para Usar

El archivo **seed_usuarios.sql** ahora está **100% compatible** con la nueva estructura de la base de datos.

### Pasos para Ejecutar:

1. **Importa primero**: `bd_escuela_pablo_neruda.sql`
2. **Importa después**: `seed_usuarios.sql`

✅ Todo funcionará correctamente.
