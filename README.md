# Sistema de Gestión Académica - Escuela Pablo Neruda

## Descripción
Sistema web para la gestión académica de la Escuela Primaria Pablo Neruda. Permite registrar estudiantes, acudientes, notas, y generar reportes académicos.

## Tecnologías
- **Frontend**: HTML5, CSS3 (Bootstrap 5), JavaScript Vanilla
- **Backend**: PHP 8+ Nativo (sin frameworks)
- **Base de Datos**: MySQL 8.0
- **Servidor**: Apache (WAMP/XAMPP)
- **Arquitectura**: MVC con principios SOLID

## Requisitos del Sistema
- PHP >= 8.0
- MySQL >= 8.0
- Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, pdo_mysql, mbstring

## Instalación

### 1. Clonar/Descargar el proyecto
```bash
cd c:\wamp64\www
# El proyecto debería estar en: c:\wamp64\www\pablo_neruda
```

### 2. Configurar Base de Datos
```bash
# Crear la base de datos
mysql -u root -p < database/bd_escuela_pablo_neruda.sql
```

### 3. Configurar Variables de Entorno
```bash
# Renombrar .env.example a .env
copy .env.example .env

# Editar .env con tus credenciales de base de datos
DB_HOST=localhost
DB_NAME=escuela_pablo_neruda
DB_USER=root
DB_PASS=tu_password
```

### 4. Configurar Apache
Asegúrate de que mod_rewrite esté habilitado y que el `DocumentRoot` apunte a la carpeta `public`:

```
http://localhost/pablo_neruda/public
```

### 5. Permisos de Carpetas (Linux/Mac)
```bash
chmod -R 755 logs
chmod -R 755 backups
chmod -R 755 public/uploads
```

## Acceso al Sistema

### URL
```
http://localhost/pablo_neruda/public/login
```

### Credenciales por Defecto
- **Usuario**: admin
- **Contraseña**: admin123
- **Rol**: Administrador

## Estructura del Proyecto

```
pablo_neruda/
├── config/              # Configuración
├── src/
│   ├── Core/            # Núcleo del framework
│   ├── Domain/          # Entidades y lógica de negocio
│   ├── Infrastructure/  # Implementaciones (Repositorios, Seguridad)
│   ├── Application/     # Controladores y Middleware
│   └── Presentation/    # Vistas
├── public/              # Directorio web público
│   ├── index.php        # Front controller
│   └── assets/          # CSS, JS, imágenes
├── database/            # Scripts SQL
├── logs/                # Logs del sistema
└── backups/             # Respaldos automáticos
```

## Funcionalidades Principales

### 1. Autenticación
- Login con roles (Administrador, Rector, Coordinador, Profesor)
- Control de acceso basado en roles
- Gestión de sesiones con timeout

### 2. Gestión de Estudiantes
- Registro de estudiantes con datos personales y médicos
- Vinculación con acudientes
- Alertas de alergias
- CRUD completo

### 3. Gestión de Notas
- Registro de 5 notas por período
- Cálculo automático de promedios
- Determinación de | Aprobado/Reprobado
- Edición de notas

### 4. Reportes
- Reporte individual de estudiante
- Reporte por curso
- Reportes institucionales

### 5. Gestión de Usuarios
- Crear usuarios por rol
- Asignar profesores a materias y cursos
- Gestión de cursos y materias

## Roles del Sistema

### Administrador
- Gestión completa de usuarios
- Configuración de cursos y materias
- Acceso total al sistema

### Rector
- Visualización de toda la información
- Generación de reportes institucionales
- Sin edición de notas

### Coordinador
- Gestión de estudiantes y acudientes
- Matrículas de estudiantes
- Visualización de notas
- Reportes académicos

### Profesor
- Registro y edición de notas de sus materias
- Visualización de estudiantes asignados
- Reportes de sus materias

## Seguridad

- **Contraseñas**: Hash con bcrypt (cost 12)
- **SQL Injection**: Prevención con preparados PDO
- **XSS**: Sanitización con htmlspecialchars
- **CSRF**: Tokens en formularios
- **Sesiones**: Timeout automático (30 min)

## Mantenimiento

### Respaldos de Base de Datos
```bash
# Backup manual
mysqldump -u root -p escuela_pablo_neruda > backups/backup_$(date +%Y%m%d).sql
```

### Logs
Los logs del sistema se almacenan en:
- `logs/app.log` - Log general de la aplicación
- `logs/database_errors.log` - Errores de conexión a BD

## Soporte

Para problemas o preguntas:
- Revisar la documentación técnica en `database/sistema_escuela_pablo_neruda.md`
- Verificar logs en carpeta `logs/`

## Licencia
Sistema desarrollado para la Escuela Pablo Neruda - Barrio Las Malvinas, Sector 4 Berlín

---

**Versión**: 1.0.0  
**Fecha**: Enero 2026
