# 🚨 SOLUCIÓN AL ERROR "Internal Server Error"

## El problema es: **LA BASE DE DATOS NO EXISTE**

### ✅ Solución en 3 Pasos:

---

## PASO 1: Verificar que MySQL esté corriendo

1. **Abre WAMP** (ícono en la bandeja del sistema)
2. **Verifica que el ícono esté VERDE**
3. Si está naranja o rojo:
   - Click derecho en el ícono WAMP
   - Click en "Start All Services"
   - Espera a que esté verde

---

## PASO 2: Crear la Base de Datos

### Opción A: Usando phpMyAdmin (MÁS FÁCIL)

1. **Abre tu navegador** y ve a: `http://localhost/phpmyadmin`

2. **En la pestaña "SQL"**, copia y pega este código:

```sql
CREATE DATABASE escuela_pablo_neruda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. **Click en "Ejecutar" o "Go"**

4. **En la barra lateral izquierda**, selecciona la base de datos `escuela_pablo_neruda`

5. **Ve a la pestaña "Importar"**

6. **Click en "Choose File"** y selecciona:
   ```
   c:\wamp64\www\pablo_neruda\database\bd_escuela_pablo_neruda.sql
   ```

7. **Scroll hacia abajo y click "Importar"**

8. **Repite paso 5-7** con el archivo:
   ```
   c:\wamp64\www\pablo_neruda\database\seed_usuarios.sql
   ```

---

### Opción B: Usando MySQL en Consola

1. **Abre PowerShell o CMD**

2. **Navega a la carpeta de MySQL de WAMP:**
   ```powershell
   cd C:\wamp64\bin\mysql\mysql8.0.x\bin
   ```
   (Ajusta según tu versión de MySQL)

3. **Conéctate a MySQL:**
   ```powershell
   .\mysql.exe -u root -p
   ```
   (Presiona Enter si no tienes contraseña)

4. **Ejecuta estos comandos:**
   ```sql
   CREATE DATABASE escuela_pablo_neruda;
   USE escuela_pablo_neruda;
   source c:/wamp64/www/pablo_neruda/database/bd_escuela_pablo_neruda.sql;
   source c:/wamp64/www/pablo_neruda/database/seed_usuarios.sql;
   EXIT;
   ```

---

## PASO 3: Verifica que todo funcione

### 1. **Prueba PHP:**
   Ve a: `http://localhost/pablo_neruda/public/test.php`
   
   ✅ Deberías ver "PHP funciona correctamente" y la info de PHP

### 2. **Prueba el Diagnóstico:**
   Ve a: `http://localhost/pablo_neruda/public/diagnostico.php`
   
   ✅ Deberías ver checkmarks verdes en todo

### 3. **Accede al Sistema:**
   Ve a: `http://localhost/pablo_neruda/public/login`
   
   ✅ Deberías ver la página de login

### 4. **Inicia Sesión:**
   ```
   Usuario: admin
   Contraseña: password123
   ```

---

## 🎯 Si sigue fallando:

1. **Revisa los logs de Apache:**
   ```
   c:\wamp64\logs\apache_error.log
   ```

2. **Verifica que mod_rewrite esté habilitado:**
   - Click derecho en ícono WAMP
   - Apache → Apache Modules → rewrite_module (debe tener ✓)

3. **Envíame:**
   - Screenshot de `diagnostico.php`
   - Últimas líneas del `apache_error.log`

---

## 📋 Credenciales de Prueba

Una vez que funcione:

- **Administrador**: `admin` / `password123`
- **Rector**: `rector` / `password123`
- **Coordinador**: `coordinador` / `password123`
- **Profesor**: `profesor` / `password123`

---

**¿Todo funcionó? ¡Avísame para continuar con el desarrollo!** 🚀
