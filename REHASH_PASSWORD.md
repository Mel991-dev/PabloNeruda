# 🔐 Actualizar Contraseña del Usuario Admin

## Método 1: Usar el Script Automático (Recomendado)

### 1. Accede al generador:
```
http://localhost/pablo_neruda/generar_hash.php
```

### 2. El formulario ya viene pre-llenado con:
- **Usuario:** `admin`
- **Contraseña:** `admin123`

### 3. Click en "Generar Hash y Actualizar"

### 4. Verifica el mensaje de éxito ✅

### 5. Prueba el login:
```
http://localhost/pablo_neruda/login
```
- Usuario: `admin`
- Contraseña: `admin123`

### 6. ⚠️ IMPORTANTE: Elimina el archivo después de usar
```bash
rm public/generar_hash.php
```

---

## Método 2: SQL Manual (Si el script falla)

### 1. Abre phpMyAdmin

### 2. Selecciona la base de datos: `escuela_pablo_neruda`

### 3. Ve a la pestaña "SQL"

### 4. Pega y ejecuta:
```sql
UPDATE usuarios 
SET password_hash = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY1w.1UovqRvK.S'
WHERE username = 'admin';
```

**Nota:** Este hash corresponde a la contraseña `password123`

### 5. Para usar `admin123`, ejecuta:
```sql
UPDATE usuarios 
SET password_hash = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';
```

---

## Verificar que Funcionó

1. Accede a: `http://localhost/pablo_neruda/login`
2. Ingresa:
   - Usuario: `admin`
   - Contraseña: `admin123`
3. Deberías ser redirigido al dashboard

---

## Credenciales Actuales del Sistema

Después de ejecutar `seed_usuarios.sql`:

| Usuario      | Contraseña    | Rol           |
|--------------|---------------|---------------|
| admin        | password123   | Administrador |
| rector       | password123   | Rector        |
| coordinador  | password123   | Coordinador   |
| profesor     | password123   | Profesor      |

**Para cambiar cualquier contraseña**, usa el script `generar_hash.php`.
