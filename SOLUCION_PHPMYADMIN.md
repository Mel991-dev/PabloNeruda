# 🎯 SOLUCIÓN: Error #1044 - Acceso Negado

## El problema:
Estás intentando crear tablas en `information_schema` (base de datos del sistema). 
Necesitas **primero crear tu propia base de datos**.

---

## ✅ SOLUCIÓN PASO A PASO (phpMyAdmin):

### PASO 1: Crear la base de datos

1. **Abre phpMyAdmin:** `http://localhost/phpmyadmin`

2. **Click en la pestaña "SQL"** (en la parte superior)

3. **Copia y pega SOLO esto:**

```sql
DROP DATABASE IF EXISTS escuela_pablo_neruda;
CREATE DATABASE escuela_pablo_neruda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. **Click en "Continuar" o "Go"**

5. ✅ Deberías ver: "Base de datos creada"

---

### PASO 2: Seleccionar la base de datos

1. **En el panel IZQUIERDO**, busca y **click en** `escuela_pablo_neruda`

2. Verás que dice "No hay tablas en la base de datos"

---

### PASO 3: Importar el script SQL

1. **Click en la pestaña "Importar"** (arriba)

2. **Click en "Seleccionar archivo"** o "Choose File"

3. **Navega y selecciona:**
   ```
   c:\wamp64\www\pablo_neruda\database\bd_escuela_pablo_neruda.sql
   ```

4. **Scroll hacia abajo**

5. **Click en "Continuar" o "Go"**

6. ⏳ Espera unos segundos...

7. ✅ Deberías ver: "Importación finalizada, XX consultas ejecutadas"

---

### PASO 4: Importar usuarios iniciales

1. **Asegúrate de que aún estás en** `escuela_pablo_neruda` (panel izquierdo)

2. **Click de nuevo en "Importar"**

3. **Selecciona el archivo:**
   ```
   c:\wamp64\www\pablo_neruda\database\seed_usuarios.sql
   ```

4. **Click en "Continuar"**

5. ✅ Deberías ver: "4 usuarios creados"

---

### PASO 5: Verificar que funcionó

1. **En el panel izquierdo**, deberías ver las tablas:
   - acudientes
   - cursos
   - estudiantes
   - materias
   - notas
   - profesores
   - usuarios
   - ... y más

2. **Click en la tabla "usuarios"**

3. **Click en "Examinar"**

4. ✅ Deberías ver 4 usuarios: admin, rector, coordinador, profesor

---

## 🚀 AHORA SÍ: Accede al sistema

1. Ve a: `http://localhost/pablo_neruda/public/login`

2. Usa:
   ```
   Usuario: admin
   Contraseña: password123
   ```

3. ✅ Deberías entrar al dashboard de administrador

---

## 📸 Si sigue fallando:

Envíame un screenshot de:
1. El panel izquierdo de phpMyAdmin (donde se ven las bases de datos)
2. El mensaje de error exacto que aparece

---

**¿Funcionó? ¡Avísame!** 🎉
