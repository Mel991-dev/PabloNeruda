# 🔍 Cómo Funciona el Enrutamiento - Explicación Completa

## 📚 Sistema de Enrutamiento Explicado

### 1️⃣ No Hay Archivo `login.php` Físico

**Correcto**, NO existe un archivo físico `c:\wamp64\www\pablo_neruda\login.php`.

El sistema usa **enrutamiento dinámico**:

```
URL solicitada: http://localhost/pablo_neruda/login
                          ↓
              .htaccess (raíz) redirige a public/
                          ↓
              .htaccess (public/) redirige a index.php
                          ↓
              Router en index.php procesa "/login"
                          ↓
              Ejecuta: AuthController->showLogin()
```

---

## 🔧 Cómo Funciona Paso a Paso

### **Paso 1: `.htaccess` en la raíz**
**Archivo:** `c:\wamp64\www\pablo_neruda\.htaccess`

```apache
RewriteRule ^(.*)$ public/$1 [L]
```

**Qué hace:**
- Toma TODO lo que viene después de `/pablo_neruda/`
- Lo redirige internamente a `public/`
- Ejemplo: `/pablo_neruda/login` → `/pablo_neruda/public/login`

---

### **Paso 2: `.htaccess` en public**
**Archivo:** `c:\wamp64\www\pablo_neruda\public\.htaccess`

```apache
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Qué hace:**
- Si NO es un archivo físico (`!-f`)
- Y NO es un directorio (`!-d`)
- Redirige a `index.php` con la ruta original

---

### **Paso 3: Router en `index.php`**
**Archivo:** `c:\wamp64\www\pablo_neruda\public\index.php`

```php
// Líneas 45-52 aproximadamente
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);
```

**Qué hace:**
- Lee la URI solicitada (`/login`)
- Busca en las rutas registradas
- Ejecuta el controlador correspondiente
- EN ESTE CASO: `AuthController->showLogin()`

---

## ❌ Por Qué NO Funciona Ahora

Apache está mostrando el **Directory Listing** (índice de archivos) porque:

### **🔴 Problema: `mod_rewrite` NO está habilitado**

Sin `mod_rewrite`:
- ❌ Apache NO procesa las reglas `RewriteRule`
- ❌ El `.htaccess` es ignorado
- ❌ Apache muestra la lista de archivos por defecto

---

## ✅ SOLUCIÓN: Habilitar mod_rewrite en WAMP

### **Opción 1: Desde el Ícono de WAMP (Más Fácil)**

1. **Click izquierdo en el ícono de WAMP** (bandeja del sistema)
   
2. **Apache → Apache Modules**

3. **Busca:** `rewrite_module`

4. **Si NO tiene ✓ (check):**
   - Click en `rewrite_module` para habilitarlo
   - Esto reiniciará Apache automáticamente

5. **Verifica que aparezca con ✓**

---

### **Opción 2: Editar httpd.conf Manualmente**

1. **Click en WAMP → Apache → httpd.conf**

2. **Busca la línea** (Ctrl + F):
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```

3. **Quita el `#` al inicio:**
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

4. **Guarda el archivo**

5. **Reinicia Apache:**
   - WAMP → Apache → Service → Restart Service

---

### **Paso Adicional: AllowOverride**

1. **Abre httpd.conf** (igual que arriba)

2. **Busca:** `<Directory "c:/wamp64/www/">`

3. **Verifica que diga:**
   ```apache
   <Directory "c:/wamp64/www/">
       Options +Indexes +FollowSymLinks +MultiViews
       AllowOverride All    # ← Debe decir "All"
       Require local
   </Directory>
   ```

4. **Si dice `AllowOverride None`**, cámbialo a `AllowOverride All`

5. **Guarda y reinicia Apache**

---

## 🧪 Verificar que Funcione

### **Test 1: Verificar mod_rewrite**

Crea un archivo: `c:\wamp64\www\pablo_neruda\public\phpinfo.php`

```php
<?php
phpinfo();
```

Accede a:
```
http://localhost/pablo_neruda/phpinfo.php
```

Busca (Ctrl + F): **"Loaded Modules"**

Debe aparecer: **`mod_rewrite`**

---

### **Test 2: Probar el Login**

Una vez habilitado `mod_rewrite`:

```
http://localhost/pablo_neruda/login
```

**Debe mostrar:**
- ✅ La página de login (formulario)
- ❌ NO el listado de archivos

---

## 🎯 Resumen

1. ✅ **El enrutamiento SÍ está configurado correctamente en el código**
2. ❌ **Apache NO puede procesarlo sin `mod_rewrite`**
3. 🔧 **Solución: Habilitar `mod_rewrite` en WAMP**

---

Sigue los pasos de "SOLUCIÓN" y avísame cuando lo hayas habilitado. 🚀
