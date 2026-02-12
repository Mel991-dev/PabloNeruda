# Sistema de Gestión Académica - Escuela Pablo Neruda

## Descripción
Sistema web integral para la gestión académica, administrativa y de orientación escolar de la Escuela Primaria Pablo Neruda. Diseñado para optimizar procesos como matrículas, calificaciones, seguimiento psicosocial y generación de reportes, facilitando la toma de decisiones basada en datos.

## Tecnologías
- **Frontend**: HTML5, CSS3 (Bootstrap 5, Chart.js), JavaScript Vanilla
- **Backend**: PHP 8+ Nativo (Arquitectura MVC, Principios SOLID)
- **Base de Datos**: MySQL 8.0
- **Servidor**: Apache (WAMP/XAMPP)
- **Seguridad**: Hash de contraseñas (bcrypt), protección contra SQL Injection y XSS.

## Instalación y Configuración

1. **Clonar el Repositorio**:
   ```bash
   git clone https://github.com/Mel991-dev/PabloNeruda.git
   ```

2. **Base de Datos**:
   - Importar el script `database/bd_escuela_pablo_neruda.sql` en MySQL.
   - Configurar credenciales en `.env` (copiar de `.env.example`).

3. **Servidor Web**:
   - Apuntar el `DocumentRoot` de Apache a la carpeta `public/`.
   - Asegurar que `mod_rewrite` esté habilitado.

## Módulos y Funcionalidades Principales

### 1. Dashboard & Analítica Avanzada
Un panel de control centralizado con visualización de datos en tiempo real:
- **Indicadores Clave (KPIs)**: Total de estudiantes, profesores, cursos y usuarios activos.
- **Gráficos de Rendimiento**:
    - *Barras*: Top 5 materias con mayor tasa de reprobación.
    - *Circular*: Distribución global del rendimiento (Superior, Alto, Básico, Bajo).

### 2. Gestión Académica (Estudiantes y Matrículas)
- **Hoja de Vida Estudiantil**: Datos personales completos, información médica (alergias), y antecedentes.
- **Núcleo Familiar**: Gestión detallada de padres y acudientes, incluyendo nivel educativo, ocupación y contacto.
- **Matrículas**: Asignación de estudiantes a cursos y años lectivos.

### 3. Calificaciones y Boletines
- **Registro de Notas**: Sistema de 5 notas parciales por periodo con cálculo automático de promedios.
- **Boletines Inteligentes**: 
    - Generación de boletines por periodo (1-4).
    - Visualización de **todas las materias** del plan de estudios (incluso sin notas registradas).
    - Modal de selección de periodo intuitivo.

### 4. Módulo de Orientación Escolar
Herramienta especializada para el seguimiento psicosocial:
- **Citaciones**: Agendamiento y control de citas con padres/acudientes.
- **Seguimiento de Casos**: Registro detallado de sesiones, observaciones y compromisos.
- **Alertas Tempranas**: Identificación de estudiantes en riesgo académico o convivencial.

### 5. Administración del Sistema (Roles y Permisos)
- **Administrador**: Control total, gestión de usuarios, cursos y materias.
- **Rector**: Visión gerencial, reportes institucionales y monitoreo de desempeño.
- **Coordinador**: Gestión operativa de matrículas y convivencia.
- **Profesor**: Registro de notas y consulta de listados de clase.
- **Orientador**: Gestión exclusiva del módulo de orientación.

## Estructura del Proyecto
```
pablo_neruda/
├── config/              # Configuración global y de BD
├── src/
│   ├── Application/     # Controladores (Lógica de entrada)
│   ├── Domain/          # Entidades y Servicios (Lógica de negocio)
│   ├── Infrastructure/  # Repositorios (Acceso a datos)
│   └── Presentation/    # Vistas (HTML/PHP, Assets)
├── public/              # Punto de entrada (index.php) y recursos estáticos
├── database/            # Scripts SQL y documentación de esquema
└── logs/                # Registros de errores y actividad
```

## Soporte y Mantenimiento
- **Logs**: Revisar `logs/app.log` para depuración.
- **Backups**: Scripts de respaldo disponibles en `backups/`.

---
**Versión**: 1.2.0 (Febrero 2026)
**Desarrollado para**: Escuela Pablo Neruda - Barrio Las Malvinas.
