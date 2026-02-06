# Guía de Instalación Rápida
## Sistema de Gestión Académica - Escuela Pablo Neruda

### ⚡ Instalación en 5 Pasos

#### 0️⃣ **Verificar Requisitos (EJECUTAR PRIMERO)**
```
URL: http://localhost/pablo_neruda/public/diagnostico.php
```
Esta página te mostrará:
- ✅ Si PHP y extensiones están instaladas
- ✅ Si los archivos existen
- ✅ Si la base de datos está creada
- ✅ Qué errores hay y cómo solucionarlos

#### 1️⃣ **Crear la Base de Datos**
```bash
# Desde MySQL o phpMyAdmin en WAMP
mysql -u root -p

# Ejecutar el script principal
source c:/wamp64/www/pablo_neruda/database/bd_escuela_pablo_neruda.sql

# Ejecutar el script de usuarios iniciales
source c:/wamp64/www/pablo_neruda/database/seed_usuarios.sql
```

#### 2️⃣ **Configurar Variables de Entorno**
```bash
# El archivo .env ya está creado, solo verifica las credenciales:
DB_HOST=localhost
DB_NAME=escuela_pablo_neruda
DB_USER=root
DB_PASS=          # Tu contraseña de MySQL (vacío si no tienes)
```

#### 3️⃣ **Acceder al Sistema**
```
URL: http://localhost/pablo_neruda/public/login
```

#### 4️⃣ **Credenciales de Prueba**
```
Administrador:   admin / password123
Rector:          rector / password123
Coordinador:     coordinador / password123
Profesor:        profesor / password123
```

#### 5️⃣ **Verificar Funcionamiento**
- ✅ Inicia sesión con cualquier usuario
- ✅ Verifica que el dashboard aparezca correctamente
- ✅ Navega por el menú lateral
- ✅ Cierra sesión

---

### 🔧 Solución de Problemas

**Error: "Página no encontrada"**
- Verifica que mod_rewrite esté habilitado en Apache
- Asegúrate de acceder a: `/pablo_neruda/public/login`

**Error: "No se puede conectar a la base de datos"**
- Verifica las credenciales en el archivo `.env`
- Asegúrate de que MySQL esté corriendo
- Confirma que la base de datos `escuela_pablo_neruda` existe

**Error: "Credenciales inválidas"**
- Verifica que hayas ejecutado `seed_usuarios.sql`
- Usa las credenciales exactas (case-sensitive)

---

### 📝 Próximos Pasos

1. **Cambiar contraseñas por defecto** (en producción)
2. **Registrar estudiantes** (desde el menú Coordinador)
3. **Asignar materias a profesores** (desde Administrador)
4. **Registrar notas** (desde perfil Profesor)

---

### 📞 Soporte
Ver archivo completo `README.md` para más detalles.
