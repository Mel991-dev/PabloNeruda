# ✅ Configuración de .htaccess Corregida

## Problema Encontrado

Había un **loop de redirección** entre dos archivos `.htaccess`:

1. **Raíz** (`/.htaccess`): Redirigía TODO a `public/`
2. **Public** (`/public/.htaccess`): Intentaba procesar la ruta y volver a redirigir

Esto causaba el Internal Server Error 500.

---

## Solución Aplicada

### `.htaccess` en la raíz (`c:\wamp64\www\pablo_neruda\.htaccess`)

```apache
# Redirigir todo al directorio public (excepto archivos existentes)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} !^/pablo_neruda/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Cambios:**
- ✅ Solo redirige si el archivo NO existe
- ✅ Solo redirige si NO es un directorio
- ✅ Solo redirige si NO estamos ya en `/public/`

---

### `.htaccess` en public (`c:\wamp64\www\pablo_neruda\public\.htaccess`)

```apache
# Habilitar rewrite
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirigir todo a index.php excepto archivos y directorios existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

**Cambios:**
- ✅ Eliminado `RewriteBase` (causaba conflicto)
- ✅ Mantiene protección de archivos existentes

---

## URLs Correctas Ahora

✅ **Login:**
```
http://localhost/pablo_neruda/login
```

✅ **Diagnóstico:**
```
http://localhost/pablo_neruda/diagnostico.php
```

✅ **Test PHP:**
```
http://localhost/pablo_neruda/test.php
```

---

## Listo para Probar

Actualiza tu navegador (Ctrl + F5) y accede a:
```
http://localhost/pablo_neruda/login
```

Usuario: `admin` / Contraseña: `password123`
