# Sistema de Gestión Académica - Escuela Pablo Neruda
## Análisis y Diseño Completo del Sistema

---

## 📋 ÍNDICE
1. Análisis del Problema
2. Actores del Sistema
3. Requerimientos Funcionales Completos
4. Correcciones a la Base de Datos Actual
5. Diagrama Entidad-Relación Mejorado
6. Diagrama de Casos de Uso
7. Diagramas de Flujo por Actor
8. Arquitectura del Sistema
9. Diccionario de Datos Completo
10. Recomendaciones de Implementación

---

## 1. 📊 ANÁLISIS DEL PROBLEMA

### Situación Actual
- **Problema principal**: Gestión manual en cuadernos físicos
- **Consecuencias**: Errores, pérdida de información, demoras en consultas
- **Necesidad crítica**: Acceso rápido a datos en emergencias

### Solución Propuesta
Sistema de información web que permita:
- Centralización de datos de estudiantes
- Gestión de calificaciones automatizada
- Consultas rápidas y reportes
- Respaldo automático de información
- Control de acceso por roles

---

## 2. 👥 ACTORES DEL SISTEMA

### Actor 1: **ADMINISTRADOR DEL SISTEMA**
- **Rol**: Gestión técnica y configuración del sistema
- **Permisos**: Acceso total
- **Responsabilidades**:
  - Crear y gestionar usuarios (profesores, coordinadores, rector)
  - Configurar períodos académicos
  - Gestionar cursos y materias
  - Realizar respaldos del sistema
  - Generar reportes globales

### Actor 2: **RECTOR**
- **Rol**: Dirección y supervisión académica
- **Permisos**: Visualización total, sin edición de notas
- **Responsabilidades**:
  - Consultar información de todos los estudiantes
  - Generar reportes académicos institucionales
  - Visualizar estadísticas generales
  - Revisar aprobaciones/reprobaciones por curso
  - Exportar datos para informes oficiales

### Actor 3: **COORDINADOR**
- **Rol**: Gestión académica y administrativa
- **Permisos**: Gestión de estudiantes y consulta de notas
- **Responsabilidades**:
  - Registrar nuevos estudiantes
  - Actualizar información de estudiantes y acudientes
  - Asignar estudiantes a cursos
  - Consultar notas y promedios
  - Generar reportes por curso/materia
  - Gestionar alertas de estudiantes con alergias

### Actor 4: **PROFESOR**
- **Rol**: Docencia y evaluación
- **Permisos**: Gestión de notas de sus materias asignadas
- **Responsabilidades**:
  - Registrar notas de estudiantes (5 notas por período)
  - Consultar listados de sus cursos
  - Ver información básica de estudiantes (incluyendo alergias)
  - Generar reportes de su materia
  - Visualizar automáticamente aprobación/reprobación

### Actor 5: **ESTUDIANTE/ACUDIENTE** (Opcional - Fase 2)
- **Rol**: Consulta de información personal
- **Permisos**: Solo lectura de datos propios
- **Responsabilidades**:
  - Consultar notas propias
  - Ver promedio y estado (aprobado/reprobado)
  - Actualizar datos de contacto del acudiente

---

## 3. ✅ REQUERIMIENTOS FUNCIONALES COMPLETOS

### RF-001: Gestión de Usuarios
- El sistema debe permitir al administrador crear usuarios con roles específicos
- Cada usuario debe tener: username, password (encriptado), rol, estado (activo/inactivo)

### RF-002: Gestión de Estudiantes
- El coordinador puede registrar estudiantes con los siguientes datos:
  - Datos básicos: nombre, apellido, fecha de nacimiento, tipo de documento, número de documento
  - Datos académicos: curso asignado, jornada (mañana/tarde)
  - Datos médicos: tiene alergias (Sí/No), descripción de alergias
  - Documento: archivo PDF del documento de identidad
  - Estado: activo/retirado

### RF-003: Gestión de Acudientes
- El coordinador puede registrar acudientes con:
  - Datos personales: nombre, apellido, tipo de documento, número de documento
  - Datos de contacto: teléfono, email, dirección
  - Relación: parentesco con el estudiante
- Un estudiante puede tener múltiples acudientes (padre, madre, abuelo, etc.)

### RF-004: Gestión de Cursos
- El administrador puede crear cursos con:
  - Nombre del grado (Preescolar, 1°, 2°, 3°, 4°, 5°)
  - Sección (A, B, C)
  - Año lectivo
  - Capacidad máxima (35 estudiantes)
  - Jornada (mañana/tarde)

### RF-005: Gestión de Materias
- El administrador puede crear materias por grado
- Materias registradas: Matemáticas, Español, Informática, Inglés, Religión, Ética, Biología, Tecnología, Artística, Educación Física, Sociales

### RF-006: Asignación Profesor-Materia-Curso
- El coordinador asigna profesores a materias específicas de cursos específicos
- Un profesor puede tener múltiples asignaciones

### RF-007: Gestión de Notas
- El profesor registra 5 notas por período (1, 2, 3, 4) para cada estudiante en su materia
- Cada nota tiene un rango de 0.0 a 5.0
- El sistema calcula automáticamente:
  - Promedio del período = (Nota1 + Nota2 + Nota3 + Nota4 + Nota5) / 5
  - Estado: Aprobado si promedio ≥ 3.0, Reprobado si promedio < 3.0

### RF-008: Reportes
- **Reporte de estudiante individual**: Todas sus notas, promedios y estado
- **Reporte por curso**: Lista de estudiantes con promedios
- **Reporte por materia**: Estadísticas de aprobación/reprobación
- **Reporte institucional**: Estadísticas generales por grado

### RF-009: Búsquedas y Consultas
- Búsqueda rápida de estudiantes por: nombre, documento, curso
- Filtros por: grado, jornada, estado, alergias
- Consulta de historial académico

### RF-010: Alertas y Notificaciones
- Alerta visual cuando un estudiante con alergias está en la lista
- Notificación cuando un estudiante está en riesgo de reprobar (promedio < 3.0)

---

## 4. 🔧 CORRECCIONES A LA BASE DE DATOS ACTUAL

### ❌ ERRORES IDENTIFICADOS EN TU DISEÑO:

#### Error 1: **Dependencia Circular o Mal Diseño en Relaciones**
```
profesores → fk_materia → materias
materias → id_materia
```
**Problema**: Un profesor NO debe estar vinculado directamente a UNA sola materia. Un profesor puede enseñar múltiples materias en múltiples cursos.

**Solución**: Crear tabla intermedia `profesor_materia_curso`

#### Error 2: **Tabla "alergias" separada innecesaria**
```
alergias (id_alergias, tipo_al...?, ?)
estudiantes → ¿relación con alergias?
```
**Problema**: No se ve clara la relación. Las alergias son atributos del estudiante, no entidades independientes.

**Solución**: Integrar en la tabla `estudiantes`:
- `tiene_alergias` (BOOLEAN)
- `descripcion_alergias` (VARCHAR(500))

#### Error 3: **Tabla "cursos" con campos ambiguos**
```
cursos (id_curso, nombre_curso ?, sub_categoria ?)
```
**Problema**: 
- ¿Qué es "sub_categoria"? 
- Falta el concepto de "grado" y "sección"
- No se distingue entre 1°A, 1°B, 1°C

**Solución**: 
```
cursos (
  id_curso,
  grado (VARCHAR: 'Preescolar', '1°', '2°', '3°', '4°', '5°'),
  seccion (VARCHAR: 'A', 'B', 'C'),
  año_lectivo (INT: 2025, 2026),
  jornada (ENUM: 'Mañana', 'Tarde'),
  capacidad_maxima (INT: 35)
)
```

#### Error 4: **Falta tabla intermedia "inscripciones" o "matriculas"**
**Problema**: No hay forma de registrar en qué curso está un estudiante en un año específico.

**Solución**: Crear tabla `matriculas`:
```
matriculas (
  id_matricula,
  fk_estudiante,
  fk_curso,
  año_lectivo,
  estado (ENUM: 'Activo', 'Retirado', 'Graduado')
)
```

#### Error 5: **Tabla "acudientes" sin relación con estudiantes**
**Problema**: No hay tabla intermedia para relacionar estudiantes con sus acudientes (un estudiante puede tener 2+ acudientes).

**Solución**: Crear tabla `estudiante_acudiente`:
```
estudiante_acudiente (
  id_estudiante_acudiente,
  fk_estudiante,
  fk_acudiente,
  parentesco (VARCHAR: 'Padre', 'Madre', 'Abuelo', 'Tío', 'Otro'),
  es_acudiente_principal (BOOLEAN)
)
```

#### Error 6: **Campo "hermanos" mal ubicado**
```
acudientes → hermanos (ENUM)
```
**Problema**: Los hermanos son atributos del ESTUDIANTE, no del acudiente.

**Solución**: 
- Eliminar de `acudientes`
- Agregar a `estudiantes`: `numero_hermanos` (INT)
- O mejor: crear tabla `hermanos` que relacione estudiantes entre sí

#### Error 7: **Gestión de notas incompleta**
**Problema**: No tienes una estructura clara para las 5 notas por período.

**Solución**: Crear tabla `notas`:
```
notas (
  id_nota,
  fk_matricula,
  fk_materia,
  fk_profesor,
  periodo (INT: 1, 2, 3, 4),
  nota_1 (DECIMAL(3,2)),
  nota_2 (DECIMAL(3,2)),
  nota_3 (DECIMAL(3,2)),
  nota_4 (DECIMAL(3,2)),
  nota_5 (DECIMAL(3,2)),
  promedio_periodo (DECIMAL(3,2)) [CALCULADO],
  estado (ENUM: 'Aprobado', 'Reprobado') [CALCULADO],
  fecha_registro
)
```

#### Error 8: **Falta tabla de usuarios/autenticación**
**Problema**: No hay forma de gestionar el login de profesores, coordinadores, rectores.

**Solución**: Crear tabla `usuarios`:
```
usuarios (
  id_usuario,
  username (VARCHAR UNIQUE),
  password_hash (VARCHAR),
  rol (ENUM: 'Administrador', 'Rector', 'Coordinador', 'Profesor'),
  fk_profesor (nullable, si el rol es 'Profesor'),
  estado (ENUM: 'Activo', 'Inactivo'),
  fecha_creacion,
  ultimo_acceso
)
```

---

## 5. 🗂️ DIAGRAMA ENTIDAD-RELACIÓN MEJORADO

### Tablas del Sistema:

```
┌─────────────────────┐
│      USUARIOS       │
├─────────────────────┤
│ PK id_usuario       │
│    username         │
│    password_hash    │
│    rol              │
│    estado           │
│    fecha_creacion   │
│    ultimo_acceso    │
└─────────────────────┘

┌─────────────────────┐
│     PROFESORES      │
├─────────────────────┤
│ PK id_profesor      │
│    nombre           │
│    apellido         │
│    documento        │
│    fk_materia       │ ← ELIMINAR ESTO (es el error)
└─────────────────────┘

┌─────────────────────┐
│     ESTUDIANTES     │
├─────────────────────┤
│ PK id_estudiante    │
│    nombre           │
│    apellido         │
│    fecha_nacimiento │
│    tipo_documento   │
│    numero_documento │
│    registro_civil   │
│    tarjeta_identidad│
│    documento_pdf    │
│    tiene_alergias   │
│    desc_alergias    │
│    numero_hermanos  │
│    estado           │
└─────────────────────┘

┌─────────────────────┐
│     ACUDIENTES      │
├─────────────────────┤
│ PK id_acudiente     │
│    nombre           │
│    apellido         │
│    tipo_documento   │
│    numero_documento │
│    telefono         │
│    email            │
│    direccion        │
└─────────────────────┘

┌──────────────────────┐
│ ESTUDIANTE_ACUDIENTE │
├──────────────────────┤
│ PK id_est_acud       │
│ FK fk_estudiante     │
│ FK fk_acudiente      │
│    parentesco        │
│    es_principal      │
│    con_quien_vive    │
└──────────────────────┘

┌─────────────────────┐
│       CURSOS        │
├─────────────────────┤
│ PK id_curso         │
│    grado            │
│    seccion          │
│    año_lectivo      │
│    jornada          │
│    capacidad_max    │
└─────────────────────┘

┌─────────────────────┐
│     MATRICULAS      │
├─────────────────────┤
│ PK id_matricula     │
│ FK fk_estudiante    │
│ FK fk_curso         │
│    año_lectivo      │
│    fecha_matricula  │
│    estado           │
└─────────────────────┘

┌─────────────────────┐
│      MATERIAS       │
├─────────────────────┤
│ PK id_materia       │
│    nombre           │
│    grado_aplicable  │
│    intensidad_horas │
└─────────────────────┘

┌───────────────────────┐
│ PROFESOR_MATERIA_CURSO│
├───────────────────────┤
│ PK id_asignacion      │
│ FK fk_profesor        │
│ FK fk_materia         │
│ FK fk_curso           │
│    año_lectivo        │
└───────────────────────┘

┌─────────────────────┐
│       NOTAS         │
├─────────────────────┤
│ PK id_nota          │
│ FK fk_matricula     │
│ FK fk_materia       │
│ FK fk_profesor      │
│    periodo          │
│    nota_1           │
│    nota_2           │
│    nota_3           │
│    nota_4           │
│    nota_5           │
│    promedio_periodo │
│    estado           │
│    fecha_registro   │
│    observaciones    │
└─────────────────────┘
```

### Relaciones:

```
USUARIOS (1) ←→ (0..1) PROFESORES
  Un usuario puede ser un profesor

ESTUDIANTES (1) ←→ (M) ESTUDIANTE_ACUDIENTE (M) ←→ (1) ACUDIENTES
  Muchos a Muchos: Un estudiante tiene varios acudientes, un acudiente puede tener varios estudiantes (hermanos)

ESTUDIANTES (1) ←→ (M) MATRICULAS (M) ←→ (1) CURSOS
  Un estudiante puede tener varias matrículas (una por año), un curso tiene muchos estudiantes

PROFESORES (1) ←→ (M) PROFESOR_MATERIA_CURSO (M) ←→ (1) MATERIAS
PROFESORES (1) ←→ (M) PROFESOR_MATERIA_CURSO (M) ←→ (1) CURSOS
  Un profesor enseña varias materias en varios cursos

MATRICULAS (1) ←→ (M) NOTAS (M) ←→ (1) MATERIAS
  Una matrícula tiene muchas notas (una por materia por período)

NOTAS (M) ←→ (1) PROFESORES
  Un profesor registra muchas notas
```

---

## 6. 📐 DIAGRAMA DE CASOS DE USO

### Actores y Casos de Uso:

```
┌──────────────────────────────────────────────────────────────────┐
│                    SISTEMA ESCUELA PABLO NERUDA                   │
└──────────────────────────────────────────────────────────────────┘

        👤 ADMINISTRADOR               👤 RECTOR
               │                            │
               │                            │
               ├──→ CU-01: Gestionar Usuarios
               │                            │
               ├──→ CU-02: Configurar Cursos│
               │                            │
               ├──→ CU-03: Gestionar Materias
               │                            │
               │                            ├──→ CU-11: Consultar Información General
               │                            │
               │                            ├──→ CU-12: Generar Reportes Institucionales
               │                            │
                                            ├──→ CU-13: Ver Estadísticas Académicas


        👤 COORDINADOR                👤 PROFESOR
               │                            │
               │                            │
               ├──→ CU-04: Registrar Estudiante
               │                            │
               ├──→ CU-05: Registrar Acudiente
               │                            │
               ├──→ CU-06: Asignar Estudiante a Curso
               │                            │
               ├──→ CU-07: Asignar Profesor a Materia/Curso
               │                            │
               ├──→ CU-08: Consultar Información Estudiante
               │                            │
               ├──→ CU-09: Actualizar Información
               │                            │
               │                            ├──→ CU-14: Registrar Notas
               │                            │
               │                            ├──→ CU-15: Consultar Listado de Estudiantes
               │                            │
               │                            ├──→ CU-16: Ver Promedio Automático
               │                            │
               │                            ├──→ CU-17: Generar Reporte de Materia
               │                            │
               │                            ├──→ CU-18: Ver Alertas de Alergias


        👤 ESTUDIANTE/ACUDIENTE (Opcional)
               │
               ├──→ CU-19: Consultar Notas Propias
               │
               ├──→ CU-20: Ver Promedio y Estado
               │
               └──→ CU-21: Actualizar Datos de Contacto
```

### Descripción de Casos de Uso Principales:

#### **CU-14: Registrar Notas** (PROFESOR)

**Descripción**: El profesor ingresa las 5 notas de un estudiante en su materia para un período específico.

**Precondiciones**: 
- El profesor debe estar autenticado
- El profesor debe estar asignado a esa materia y curso

**Flujo Principal**:
1. El profesor selecciona el curso y materia
2. El sistema muestra la lista de estudiantes
3. El profesor selecciona un estudiante
4. El profesor selecciona el período (1, 2, 3 o 4)
5. El profesor ingresa las 5 notas (0.0 - 5.0)
6. El sistema valida que las notas estén en el rango válido
7. El sistema calcula automáticamente el promedio: (N1+N2+N3+N4+N5)/5
8. El sistema determina el estado: Aprobado (≥3.0) o Reprobado (<3.0)
9. El sistema guarda las notas
10. El sistema muestra un mensaje de confirmación con el promedio y estado

**Flujo Alternativo**:
- 6a. Si alguna nota está fuera del rango: mostrar error y solicitar corrección
- 5a. Si ya existen notas para ese período: permitir edición

**Postcondiciones**: Las notas quedan registradas y el promedio calculado

---

## 7. 🔄 DIAGRAMAS DE FLUJO POR ACTOR

### FLUJO 1: PROFESOR - Registro de Notas

```
INICIO
   │
   ▼
┌─────────────────────┐
│ Profesor ingresa    │
│ al sistema          │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Validar credenciales│
└──────────┬──────────┘
           │
           ▼
      ¿Válidas?
      /      \
     NO      SÍ
     │        │
     ▼        ▼
  [Error]  ┌──────────────────┐
           │ Mostrar Dashboard│
           │ del Profesor     │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Seleccionar      │
           │ Curso y Materia  │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Sistema muestra  │
           │ lista estudiantes│
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Seleccionar      │
           │ Estudiante       │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Seleccionar      │
           │ Período (1-4)    │
           └────────┬─────────┘
                    │
                    ▼
      ¿Ya existen notas?
      /              \
    SÍ               NO
     │                │
     ▼                │
┌──────────┐          │
│ Mostrar  │          │
│ notas    │          │
│ actuales │          │
└────┬─────┘          │
     │                │
     └────────┬───────┘
              │
              ▼
     ┌──────────────────┐
     │ Ingresar/Editar  │
     │ Nota 1 (0.0-5.0) │
     └────────┬─────────┘
              │
              ▼
     ┌──────────────────┐
     │ Ingresar Nota 2  │
     └────────┬─────────┘
              │
              ▼
     ┌──────────────────┐
     │ Ingresar Nota 3  │
     └────────┬─────────┘
              │
              ▼
     ┌──────────────────┐
     │ Ingresar Nota 4  │
     └────────┬─────────┘
              │
              ▼
     ┌──────────────────┐
     │ Ingresar Nota 5  │
     └────────┬─────────┘
              │
              ▼
     ┌──────────────────┐
     │ Validar notas    │
     │ (rango 0.0-5.0)  │
     └────────┬─────────┘
              │
              ▼
         ¿Válidas?
        /        \
       NO        SÍ
       │          │
       ▼          ▼
   [Mostrar]  ┌──────────────────┐
   [Error y]  │ Calcular Promedio│
   [volver ]  │ = (N1+..+N5)/5   │
              └────────┬─────────┘
                       │
                       ▼
              ┌──────────────────┐
              │ Determinar Estado│
              │ Promedio ≥ 3.0?  │
              └────────┬─────────┘
                       │
                  /────┴────\
                 /           \
             ≥ 3.0          < 3.0
                │             │
                ▼             ▼
         ┌───────────┐  ┌───────────┐
         │ Estado =  │  │ Estado =  │
         │ APROBADO  │  │ REPROBADO │
         └─────┬─────┘  └─────┬─────┘
               │              │
               └──────┬───────┘
                      │
                      ▼
            ┌──────────────────┐
            │ Guardar notas    │
            │ en base de datos │
            └────────┬─────────┘
                     │
                     ▼
            ┌──────────────────┐
            │ Mostrar mensaje: │
            │ "Notas guardadas"│
            │ Promedio: X.XX   │
            │ Estado: XXXX     │
            └────────┬─────────┘
                     │
                     ▼
            ¿Registrar más notas?
               /          \
             SÍ           NO
              │            │
              │            ▼
              │         [FIN]
              │
              └─────────┐
                        │
                        ▼
               [Volver a lista
                de estudiantes]
```

---

### FLUJO 2: COORDINADOR - Registro de Estudiante

```
INICIO
   │
   ▼
┌─────────────────────┐
│ Coordinador ingresa │
│ al sistema          │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Validar credenciales│
└──────────┬──────────┘
           │
           ▼
      ¿Válidas?
      /      \
     NO      SÍ
     │        │
     ▼        ▼
  [Error]  ┌──────────────────┐
           │ Mostrar Dashboard│
           │ Coordinador      │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Seleccionar      │
           │ "Registrar       │
           │  Estudiante"     │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ FORMULARIO       │
           │ Datos Básicos:   │
           │ - Nombre         │
           │ - Apellido       │
           │ - Fecha nac.     │
           │ - Tipo doc.      │
           │ - Núm. doc.      │
           │ - Registro civil │
           │ - Tarjeta ident. │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Datos Académicos:│
           │ - Seleccionar    │
           │   Curso          │
           │ - Jornada        │
           │   (Mañana/Tarde) │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Datos Médicos:   │
           │ ¿Tiene alergias? │
           └────────┬─────────┘
                    │
                    ▼
              ¿Tiene alergias?
              /            \
            SÍ             NO
             │              │
             ▼              │
    ┌──────────────┐        │
    │ Ingresar     │        │
    │ descripción  │        │
    │ de alergias  │        │
    └──────┬───────┘        │
           │                │
           └────────┬───────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Subir documento  │
           │ PDF (identidad)  │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Otros datos:     │
           │ - Núm. hermanos  │
           └────────┬─────────┘
                    │
                    ▼
           ┌──────────────────┐
           │ Validar datos    │
           │ obligatorios     │
           └────────┬─────────┘
                    │
                    ▼
            ¿Datos completos?
            /            \
          NO              SÍ
           │               │
           ▼               ▼
    [Mostrar]     ┌──────────────────┐
    [campos ]     │ Verificar que    │
    [faltantes]   │ curso no exceda  │
                  │ capacidad (35)   │
                  └────────┬─────────┘
                           │
                           ▼
                   ¿Hay cupo?
                   /        \
                 NO          SÍ
                  │           │
                  ▼           ▼
          [Mostrar]   ┌──────────────────┐
          [error  ]   │ Guardar estudiante
          [capacidad] │ en BD            │
                      └────────┬─────────┘
                               │
                               ▼
                      ┌──────────────────┐
                      │ Crear matrícula  │
                      │ automáticamente  │
                      └────────┬─────────┘
                               │
                               ▼
                      ┌──────────────────┐
                      │ Mostrar mensaje: │
                      │ "Estudiante      │
                      │  registrado"     │
                      │ ID: XXXXX        │
                      └────────┬─────────┘
                               │
                               ▼
                      ┌──────────────────┐
                      │ ¿Registrar       │
                      │  acudiente?      │
                      └────────┬─────────┘
                               │
                          /────┴────\
                         /           \
                       SÍ             NO
                        │              │
                        ▼              ▼
              [Ir a registro]      [FIN]
              [de acudiente ]
```

---

### FLUJO 3: RECTOR - Consulta y Reportes

```
INICIO
   │
   ▼
┌─────────────────────┐
│ Rector ingresa      │
│ al sistema          │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Validar credenciales│
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────┐
│ Dashboard Rector            │
│ - Estadísticas generales    │
│ - Alertas importantes       │
│ - Resumen académico         │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────┐
│ Menú de opciones:   │
│ 1. Consultar        │
│    estudiantes      │
│ 2. Reportes         │
│    institucionales  │
│ 3. Estadísticas     │
│ 4. Búsqueda rápida  │
└──────────┬──────────┘
           │
    ┌──────┴──────┬──────────┬──────────┐
    │             │          │          │
    ▼             ▼          ▼          ▼
[Opción 1]   [Opción 2] [Opción 3] [Opción 4]


[OPCIÓN 2: REPORTES]
    │
    ▼
┌─────────────────────┐
│ Seleccionar tipo:   │
│ A. Por curso        │
│ B. Por materia      │
│ C. Por grado        │
│ D. Institucional    │
└──────────┬──────────┘
           │
    [Ejemplo: D]
           │
           ▼
┌─────────────────────┐
│ Seleccionar filtros:│
│ - Año lectivo       │
│ - Período           │
│ - Jornada           │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Sistema consulta BD │
│ y genera reporte    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────┐
│ REPORTE INSTITUCIONAL       │
│                             │
│ Total estudiantes: XXX      │
│ Por grado:                  │
│   - Preescolar: XX          │
│   - 1°: XX                  │
│   - 2°: XX                  │
│   ...                       │
│                             │
│ Aprobación general:         │
│   - Aprobados: XX%          │
│   - Reprobados: XX%         │
│                             │
│ Por materia:                │
│   - Matemáticas: XX%        │
│   - Español: XX%            │
│   ...                       │
│                             │
│ Estudiantes con alertas:    │
│   - Con alergias: XX        │
│   - En riesgo: XX           │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────┐
│ Opciones:           │
│ - Exportar PDF      │
│ - Exportar Excel    │
│ - Imprimir          │
│ - Enviar por email  │
└──────────┬──────────┘
           │
           ▼
        [FIN]
```

---

### FLUJO 4: ADMINISTRADOR - Configuración del Sistema

```
INICIO
   │
   ▼
┌─────────────────────┐
│ Admin ingresa       │
│ al sistema          │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────┐
│ Dashboard Administrador     │
│ - Estado del sistema        │
│ - Usuarios activos          │
│ - Configuraciones pendientes│
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────┐
│ Menú de opciones:   │
│ 1. Gestionar        │
│    usuarios         │
│ 2. Configurar       │
│    cursos           │
│ 3. Gestionar        │
│    materias         │
│ 4. Respaldos        │
│ 5. Configuración    │
│    general          │
└──────────┬──────────┘
           │
    [Ejemplo: Opción 1]
           │
           ▼
┌─────────────────────┐
│ GESTIÓN DE USUARIOS │
│                     │
│ Lista de usuarios:  │
│ - Ver todos         │
│ - Crear nuevo       │
│ - Editar existente  │
│ - Desactivar        │
└──────────┬──────────┘
           │
    [Crear nuevo]
           │
           ▼
┌─────────────────────┐
│ Formulario:         │
│ - Username          │
│ - Password          │
│ - Confirmar pass    │
│ - Rol               │
│   (Rector/Coord/    │
│    Profesor)        │
└──────────┬──────────┘
           │
           ▼
      ¿Rol = Profesor?
      /            \
    SÍ              NO
     │               │
     ▼               │
┌──────────┐         │
│Seleccionar│         │
│profesor de│         │
│la tabla   │         │
│profesores │         │
└─────┬────┘          │
      │               │
      └───────┬───────┘
              │
              ▼
     ┌──────────────────┐
     │ Validar datos    │
     └────────┬─────────┘
              │
              ▼
      ¿Username existe?
      /            \
    SÍ              NO
     │               │
     ▼               ▼
  [Error]   ┌──────────────────┐
            │ Hash password    │
            │ (bcrypt/argon2)  │
            └────────┬─────────┘
                     │
                     ▼
            ┌──────────────────┐
            │ Guardar en BD    │
            └────────┬─────────┘
                     │
                     ▼
            ┌──────────────────┐
            │ Mostrar mensaje: │
            │ "Usuario creado" │
            │ Username: XXXX   │
            └────────┬─────────┘
                     │
                     ▼
                  [FIN]
```

---

## 8. 🏗️ ARQUITECTURA DEL SISTEMA

### Arquitectura de 3 Capas (Recomendada)

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                      │
│                        (Frontend)                            │
├─────────────────────────────────────────────────────────────┤
│  Tecnologías recomendadas:                                  │
│  - HTML5 + CSS3 (Bootstrap 5 o Tailwind CSS)               │
│  - JavaScript (Vanilla o React.js para SPA)                │
│  - Páginas:                                                 │
│    • Login                                                  │
│    • Dashboard (por rol)                                    │
│    • Gestión de estudiantes                                │
│    • Gestión de notas                                      │
│    • Reportes                                              │
│    • Configuración                                         │
└─────────────────────────────────────────────────────────────┘
                            ↕ HTTP/HTTPS
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE LÓGICA DE NEGOCIO                 │
│                        (Backend)                             │
├─────────────────────────────────────────────────────────────┤
│  Tecnologías recomendadas:                                  │
│  - Python (Flask o Django) o PHP (Laravel) o Node.js        │
│  - API RESTful                                              │
│  - Módulos:                                                 │
│    • auth_module (autenticación/autorización)              │
│    • student_module (gestión estudiantes)                  │
│    • grade_module (gestión de notas)                       │
│    • report_module (generación reportes)                   │
│    • user_module (gestión usuarios)                        │
│    • course_module (gestión cursos/materias)               │
│  - Funciones críticas:                                     │
│    • calculate_average(notas) -> promedio                  │
│    • determine_status(promedio) -> 'Aprobado'/'Reprobado' │
│    • validate_grade_range(nota) -> bool                    │
│    • check_course_capacity(curso) -> bool                  │
└─────────────────────────────────────────────────────────────┘
                            ↕ SQL
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE DATOS                             │
│                    (Base de Datos)                           │
├─────────────────────────────────────────────────────────────┤
│  Base de datos relacional:                                  │
│  - PostgreSQL (recomendado) o MySQL                        │
│  - Tablas: 10+ tablas (ver diseño completo)               │
│  - Vistas:                                                  │
│    • v_estudiantes_completo (join estudiantes+acudientes)  │
│    • v_notas_promedio (notas con cálculos)                │
│    • v_estadisticas_curso                                  │
│  - Triggers:                                                │
│    • trg_calcular_promedio (auto-calcula al insertar notas)│
│    • trg_verificar_capacidad (valida cupo curso)          │
│  - Stored Procedures:                                       │
│    • sp_reporte_estudiante(id)                            │
│    • sp_estadisticas_curso(id_curso)                      │
└─────────────────────────────────────────────────────────────┘
```

### Seguridad del Sistema:

```
AUTENTICACIÓN:
- Uso de sesiones PHP o JWT (JSON Web Tokens)
- Passwords hasheados con bcrypt o Argon2
- Logout automático después de 30 min de inactividad

AUTORIZACIÓN (por rol):
- Administrador: Acceso total
- Rector: Solo lectura (excepto usuarios)
- Coordinador: Lectura + escritura (estudiantes, acudientes)
- Profesor: Solo gestión de notas de sus materias

VALIDACIÓN:
- Frontend: Validación inicial (UX)
- Backend: Validación definitiva (seguridad)
- SQL: Constraints y triggers

PROTECCIÓN:
- SQL Injection: Uso de prepared statements
- XSS: Sanitización de inputs
- CSRF: Tokens CSRF en formularios
- Archivos: Validación de tipo y tamaño de PDFs
```

---

## 9. 📊 DICCIONARIO DE DATOS COMPLETO

### Tabla: **USUARIOS**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_usuario | INT | - | NO | PK | Identificador único auto-incremental |
| username | VARCHAR | 50 | NO | UNIQUE | Nombre de usuario para login |
| password_hash | VARCHAR | 255 | NO | - | Contraseña hasheada (bcrypt) |
| rol | ENUM | - | NO | - | 'Administrador', 'Rector', 'Coordinador', 'Profesor' |
| fk_profesor | INT | - | YES | FK | Referencia a profesores si rol='Profesor' |
| estado | ENUM | - | NO | - | 'Activo', 'Inactivo' (default: 'Activo') |
| fecha_creacion | DATETIME | - | NO | - | Timestamp de creación |
| ultimo_acceso | DATETIME | - | YES | - | Último login del usuario |

**Índices**:
- PRIMARY KEY (id_usuario)
- UNIQUE INDEX idx_username (username)
- INDEX idx_rol (rol)

---

### Tabla: **PROFESORES**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_profesor | INT | - | NO | PK | Identificador único auto-incremental |
| nombre | VARCHAR | 100 | NO | - | Nombre(s) del profesor |
| apellido | VARCHAR | 100 | NO | - | Apellido(s) del profesor |
| tipo_documento | ENUM | - | NO | - | 'CC', 'TI', 'CE' |
| numero_documento | VARCHAR | 20 | NO | UNIQUE | Número de documento de identidad |
| telefono | VARCHAR | 15 | YES | - | Teléfono de contacto |
| email | VARCHAR | 100 | YES | - | Correo electrónico |
| especialidad | VARCHAR | 100 | YES | - | Área de especialización |
| fecha_ingreso | DATE | - | YES | - | Fecha de ingreso a la institución |
| estado | ENUM | - | NO | - | 'Activo', 'Inactivo' |

**NOTA IMPORTANTE**: ❌ **ELIMINAR** `fk_materia` de esta tabla. Es un error de diseño.

**Índices**:
- PRIMARY KEY (id_profesor)
- UNIQUE INDEX idx_doc_profesor (numero_documento)

---

### Tabla: **ESTUDIANTES**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_estudiante | INT | - | NO | PK | Identificador único auto-incremental |
| nombre | VARCHAR | 100 | NO | - | Nombre(s) del estudiante |
| apellido | VARCHAR | 100 | NO | - | Apellido(s) del estudiante |
| fecha_nacimiento | DATE | - | NO | - | Fecha de nacimiento |
| edad | INT | - | YES | COMPUTED | Calculado: YEAR(CURDATE())-YEAR(fecha_nacimiento) |
| tipo_documento | ENUM | - | NO | - | 'RC' (Registro Civil), 'TI' (Tarjeta Identidad) |
| numero_documento | VARCHAR | 20 | NO | UNIQUE | Número de documento |
| registro_civil | VARCHAR | 30 | YES | - | Número de registro civil |
| tarjeta_identidad | VARCHAR | 30 | YES | - | Número de tarjeta de identidad |
| documento_pdf | VARCHAR | 255 | YES | - | Ruta del archivo PDF subido |
| tiene_alergias | BOOLEAN | - | NO | - | TRUE si tiene alergias (default: FALSE) |
| descripcion_alergias | VARCHAR | 500 | YES | - | Descripción detallada de alergias |
| numero_hermanos | INT | - | YES | - | Cantidad de hermanos |
| estado | ENUM | - | NO | - | 'Activo', 'Retirado', 'Graduado' |
| fecha_registro | DATETIME | - | NO | - | Timestamp de registro |

**Índices**:
- PRIMARY KEY (id_estudiante)
- UNIQUE INDEX idx_doc_estudiante (numero_documento)
- INDEX idx_alergias (tiene_alergias)
- INDEX idx_estado (estado)

---

### Tabla: **ACUDIENTES**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_acudiente | INT | - | NO | PK | Identificador único auto-incremental |
| nombre | VARCHAR | 100 | NO | - | Nombre(s) del acudiente |
| apellido | VARCHAR | 100 | NO | - | Apellido(s) del acudiente |
| tipo_documento | ENUM | - | NO | - | 'CC', 'CE', 'Pasaporte' |
| numero_documento | VARCHAR | 20 | NO | UNIQUE | Número de documento |
| telefono | VARCHAR | 15 | NO | - | Teléfono principal |
| telefono_secundario | VARCHAR | 15 | YES | - | Teléfono alternativo |
| email | VARCHAR | 100 | YES | - | Correo electrónico |
| direccion | VARCHAR | 200 | YES | - | Dirección de residencia |
| ocupacion | VARCHAR | 100 | YES | - | Ocupación laboral |

**Índices**:
- PRIMARY KEY (id_acudiente)
- UNIQUE INDEX idx_doc_acudiente (numero_documento)

---

### Tabla: **ESTUDIANTE_ACUDIENTE** (Intermedia)

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_estudiante_acudiente | INT | - | NO | PK | Identificador único |
| fk_estudiante | INT | - | NO | FK | Referencia a estudiantes |
| fk_acudiente | INT | - | NO | FK | Referencia a acudientes |
| parentesco | ENUM | - | NO | - | 'Padre', 'Madre', 'Abuelo', 'Abuela', 'Tío', 'Tía', 'Hermano', 'Otro' |
| es_acudiente_principal | BOOLEAN | - | NO | - | TRUE si es el acudiente principal |
| con_quien_vive | BOOLEAN | - | NO | - | TRUE si el estudiante vive con este acudiente |
| autorizado_recoger | BOOLEAN | - | NO | - | TRUE si puede recoger al estudiante |

**Índices**:
- PRIMARY KEY (id_estudiante_acudiente)
- UNIQUE INDEX idx_est_acud (fk_estudiante, fk_acudiente)
- INDEX idx_estudiante (fk_estudiante)
- INDEX idx_acudiente (fk_acudiente)

---

### Tabla: **CURSOS**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_curso | INT | - | NO | PK | Identificador único auto-incremental |
| grado | ENUM | - | NO | - | 'Preescolar', '1°', '2°', '3°', '4°', '5°' |
| seccion | ENUM | - | NO | - | 'A', 'B', 'C' |
| año_lectivo | INT | - | NO | - | Año (ej: 2025, 2026) |
| jornada | ENUM | - | NO | - | 'Mañana', 'Tarde' |
| capacidad_maxima | INT | - | NO | - | Máximo de estudiantes (default: 35) |
| director_grupo | INT | - | YES | FK | Profesor director del grupo |

**Índices**:
- PRIMARY KEY (id_curso)
- UNIQUE INDEX idx_curso_unico (grado, seccion, año_lectivo, jornada)

---

### Tabla: **MATRICULAS**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_matricula | INT | - | NO | PK | Identificador único auto-incremental |
| fk_estudiante | INT | - | NO | FK | Referencia a estudiantes |
| fk_curso | INT | - | NO | FK | Referencia a cursos |
| año_lectivo | INT | - | NO | - | Año de la matrícula |
| fecha_matricula | DATE | - | NO | - | Fecha de matrícula |
| estado | ENUM | - | NO | - | 'Activo', 'Retirado', 'Graduado' |

**Índices**:
- PRIMARY KEY (id_matricula)
- UNIQUE INDEX idx_matricula_unica (fk_estudiante, año_lectivo)
- INDEX idx_curso (fk_curso)

---

### Tabla: **MATERIAS**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_materia | INT | - | NO | PK | Identificador único auto-incremental |
| nombre | VARCHAR | 100 | NO | UNIQUE | Nombre de la materia |
| grado_aplicable | ENUM | - | YES | - | Grado(s) donde aplica (puede ser NULL si aplica a todos) |
| intensidad_horaria | INT | - | YES | - | Horas semanales |
| descripcion | TEXT | - | YES | - | Descripción de la materia |

**Materias a registrar**:
1. Matemáticas
2. Español
3. Informática
4. Inglés
5. Religión
6. Ética
7. Biología
8. Tecnología
9. Artística
10. Educación Física
11. Sociales

---

### Tabla: **PROFESOR_MATERIA_CURSO** (Intermedia)

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_asignacion | INT | - | NO | PK | Identificador único auto-incremental |
| fk_profesor | INT | - | NO | FK | Referencia a profesores |
| fk_materia | INT | - | NO | FK | Referencia a materias |
| fk_curso | INT | - | NO | FK | Referencia a cursos |
| año_lectivo | INT | - | NO | - | Año de la asignación |
| fecha_asignacion | DATE | - | NO | - | Fecha de asignación |

**Índices**:
- PRIMARY KEY (id_asignacion)
- UNIQUE INDEX idx_asignacion_unica (fk_profesor, fk_materia, fk_curso, año_lectivo)
- INDEX idx_profesor (fk_profesor)

---

### Tabla: **NOTAS**

| Campo | Tipo | Tamaño | Null | Clave | Descripción |
|-------|------|--------|------|-------|-------------|
| id_nota | INT | - | NO | PK | Identificador único auto-incremental |
| fk_matricula | INT | - | NO | FK | Referencia a matriculas |
| fk_materia | INT | - | NO | FK | Referencia a materias |
| fk_profesor | INT | - | NO | FK | Profesor que registra |
| periodo | INT | - | NO | - | Período académico (1, 2, 3, 4) |
| nota_1 | DECIMAL | 3,2 | YES | - | Primera nota (0.00 - 5.00) |
| nota_2 | DECIMAL | 3,2 | YES | - | Segunda nota (0.00 - 5.00) |
| nota_3 | DECIMAL | 3,2 | YES | - | Tercera nota (0.00 - 5.00) |
| nota_4 | DECIMAL | 3,2 | YES | - | Cuarta nota (0.00 - 5.00) |
| nota_5 | DECIMAL | 3,2 | YES | - | Quinta nota (0.00 - 5.00) |
| promedio_periodo | DECIMAL | 3,2 | YES | COMPUTED | Calculado: (N1+N2+N3+N4+N5)/5 |
| estado | ENUM | - | YES | COMPUTED | 'Aprobado' si promedio >= 3.0, 'Reprobado' si < 3.0 |
| observaciones | TEXT | - | YES | - | Comentarios del profesor |
| fecha_registro | DATETIME | - | NO | - | Timestamp de registro |
| fecha_modificacion | DATETIME | - | YES | - | Última modificación |

**Validaciones (Constraints)**:
```sql
CHECK (nota_1 >= 0.0 AND nota_1 <= 5.0)
CHECK (nota_2 >= 0.0 AND nota_2 <= 5.0)
CHECK (nota_3 >= 0.0 AND nota_3 <= 5.0)
CHECK (nota_4 >= 0.0 AND nota_4 <= 5.0)
CHECK (nota_5 >= 0.0 AND nota_5 <= 5.0)
CHECK (periodo IN (1, 2, 3, 4))
```

**Índices**:
- PRIMARY KEY (id_nota)
- UNIQUE INDEX idx_nota_unica (fk_matricula, fk_materia, periodo)
- INDEX idx_matricula (fk_matricula)
- INDEX idx_estado (estado)

**Trigger para cálculo automático**:
```sql
CREATE TRIGGER trg_calcular_promedio_estado
BEFORE INSERT OR UPDATE ON notas
FOR EACH ROW
BEGIN
  -- Calcular promedio
  IF NEW.nota_1 IS NOT NULL AND NEW.nota_2 IS NOT NULL AND 
     NEW.nota_3 IS NOT NULL AND NEW.nota_4 IS NOT NULL AND 
     NEW.nota_5 IS NOT NULL THEN
    SET NEW.promedio_periodo = (NEW.nota_1 + NEW.nota_2 + NEW.nota_3 + NEW.nota_4 + NEW.nota_5) / 5;
    
    -- Determinar estado
    IF NEW.promedio_periodo >= 3.0 THEN
      SET NEW.estado = 'Aprobado';
    ELSE
      SET NEW.estado = 'Reprobado';
    END IF;
  END IF;
  
  -- Actualizar fecha modificación
  SET NEW.fecha_modificacion = NOW();
END;
```

---

## 10. 💡 RECOMENDACIONES DE IMPLEMENTACIÓN

### Fase 1: Fundamentos (Semanas 1-2)
1. ✅ Instalar entorno: XAMPP/WAMP (Apache + MySQL + PHP) o Node.js + PostgreSQL
2. ✅ Crear base de datos con el script SQL corregido
3. ✅ Implementar sistema de login básico
4. ✅ Crear dashboard por rol con rutas protegidas

### Fase 2: Módulo de Estudiantes (Semanas 3-4)
1. ✅ CRUD de estudiantes (Coordinador)
2. ✅ CRUD de acudientes
3. ✅ Relacionar estudiantes con acudientes
4. ✅ Sistema de carga de archivos PDF
5. ✅ Validación de capacidad de cursos

### Fase 3: Módulo de Notas (Semanas 5-6)
1. ✅ Asignación profesor-materia-curso
2. ✅ Interfaz de registro de notas (Profesor)
3. ✅ Cálculo automático de promedios
4. ✅ Visualización de estado (Aprobado/Reprobado)
5. ✅ Alertas de estudiantes en riesgo

### Fase 4: Reportes (Semana 7)
1. ✅ Reporte individual de estudiante
2. ✅ Reporte por curso
3. ✅ Reporte institucional (Rector)
4. ✅ Exportación a PDF/Excel

### Fase 5: Refinamiento (Semana 8)
1. ✅ Sistema de búsqueda avanzada
2. ✅ Alertas visuales para alergias
3. ✅ Optimización de consultas
4. ✅ Testing y corrección de bugs
5. ✅ Manual de usuario

### Tecnologías Recomendadas:

**Opción 1 - Stack PHP (Más fácil para principiantes)**
```
Frontend: HTML + Bootstrap 5 + JavaScript
Backend: PHP 8+ (puro o Laravel)
Base de Datos: MySQL 8.0
Servidor: Apache (XAMPP)
```

**Opción 2 - Stack Python (Más moderno)**
```
Frontend: HTML + Tailwind CSS + JavaScript
Backend: Python 3.10+ con Flask
Base de Datos: PostgreSQL 14+
Servidor: Gunicorn + Nginx
```

**Opción 3 - Stack JavaScript Full (Más avanzado)**
```
Frontend: React.js
Backend: Node.js + Express
Base de Datos: PostgreSQL
Servidor: Node.js
```

### Seguridad Esencial:
1. 🔒 Nunca almacenar contraseñas en texto plano
2. 🔒 Usar prepared statements para SQL (evitar inyección)
3. 🔒 Validar SIEMPRE en backend (no confiar en frontend)
4. 🔒 Sanitizar archivos PDF subidos
5. 🔒 Implementar control de sesiones con timeout

### Respaldo:
```sql
-- Backup diario automático
mysqldump -u root -p escuela_pablo_neruda > backup_$(date +%Y%m%d).sql

-- O usar pgdump para PostgreSQL
pg_dump escuela_pablo_neruda > backup_$(date +%Y%m%d).sql
```

---

## 📋 RESUMEN DE CORRECCIONES CRÍTICAS

### ❌ Errores a corregir en tu BD actual:

1. **ELIMINAR** `fk_materia` de la tabla `profesores`
   - Un profesor puede enseñar múltiples materias
   - Crear tabla `profesor_materia_curso` en su lugar

2. **ELIMINAR** tabla `alergias` como entidad separada
   - Integrar como campos en `estudiantes`:
     - `tiene_alergias` (BOOLEAN)
     - `descripcion_alergias` (VARCHAR)

3. **CORREGIR** tabla `cursos`
   - Cambiar `sub_categoria` por:
     - `grado` (ENUM: 'Preescolar', '1°', '2°', etc.)
     - `seccion` (ENUM: 'A', 'B', 'C')
     - `año_lectivo` (INT)
     - `jornada` (ENUM: 'Mañana', 'Tarde')

4. **AGREGAR** tabla `matriculas`
   - Para registrar en qué curso está cada estudiante por año

5. **AGREGAR** tabla `estudiante_acudiente`
   - Relación muchos a muchos
   - Campos: `parentesco`, `es_principal`, `con_quien_vive`

6. **MOVER** campo `hermanos` 
   - De `acudientes` a `estudiantes`
   - Renombrar a `numero_hermanos` (INT)

7. **MEJORAR** tabla `notas`
   - Agregar campos faltantes: `fk_matricula`, `fk_profesor`, `periodo`
   - Separar las 5 notas: `nota_1`, `nota_2`, ... `nota_5`
   - Agregar campos calculados: `promedio_periodo`, `estado`

8. **AGREGAR** tabla `usuarios`
   - Para login del sistema
   - Campos: `username`, `password_hash`, `rol`, `estado`

---

## ✅ CONCLUSIÓN

Este es un sistema completo y robusto que soluciona todos los problemas planteados. La arquitectura propuesta:

- ✅ Elimina errores y dependencias circulares
- ✅ Sigue principios de normalización de base de datos
- ✅ Implementa lógica de negocio clara
- ✅ Separa responsabilidades por rol
- ✅ Escala fácilmente para futuras mejoras
- ✅ Cumple con todos los requerimientos funcionales

**Próximo paso**: Implementar el script SQL con las correcciones y comenzar a codificar el sistema siguiendo la arquitectura propuesta.

---

*Documento generado para: Escuela Pablo Neruda - Barrio Las Malvinas, Sector 4 Berlín*  
*Fecha: Enero 2026*  
*Versión: 1.0*
