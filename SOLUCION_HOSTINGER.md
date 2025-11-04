# Solución para Configurar Git en Hostinger

## Paso 1: Habilitar SSH

1. Ve a **Avanzado** → **Acceso SSH**
2. Activa SSH si no está habilitado
3. Anota tus credenciales SSH (usuario, contraseña/clave)

## Paso 2: Conectarte por SSH

### En Windows:
Usa **PuTTY** o **Git Bash** o **PowerShell**:

```bash
ssh usuario@tu-servidor-hostinger.com
```

### En Mac/Linux:
```bash
ssh usuario@tu-servidor-hostinger.com
```

## Paso 3: Configurar Git

Una vez conectado por SSH, ejecuta:

```bash
# Navegar al directorio del proyecto
cd public_html

# O si tu proyecto está en otra carpeta, ajusta la ruta
cd /ruta/a/tu/proyecto

# Configurar Git para usar merge en lugar de rebase
git config pull.rebase false

# Verificar que se configuró correctamente
git config --get pull.rebase
# Debe mostrar: false
```

## Paso 4: Probar el Deployment

1. Ve de vuelta al panel de Hostinger
2. En **GIT** → **Gestionar repositorios**
3. Haz clic en **Implementar** junto a tu repositorio
4. Debería funcionar ahora sin el error de ramas divergentes

---

## Alternativa: Si NO tienes acceso SSH

### Contactar Soporte de Hostinger

1. Ve a **Soporte** en tu panel de Hostinger
2. Abre un ticket o chat
3. Pide que ejecuten en tu repositorio:
   ```
   git config pull.rebase false
   ```
4. O que modifiquen el comando de deployment para usar:
   ```
   git pull --no-rebase origin main
   ```

---

## Verificar que Funcionó

Después de configurar, intenta el deployment nuevamente. Si ves:
- ✅ "Deployment successful" o similar
- ✅ Sin errores de "divergent branches"

Entonces está funcionando.

---

## IMPORTANTE: Después del Deployment

1. **Crea el archivo `config/config.ini`** en el servidor:
   - Ve a **Archivos** → **File Manager**
   - Navega a `public_html/config/`
   - Crea nuevo archivo `config.ini`
   - Agrega:
   ```ini
   server = "localhost"
   username = "u625329211_preguntados"
   password = "+MpWww?d$5P"
   db_name = "u625329211_preguntados"
   ```

2. **Importa la base de datos**:
   - Ve a **Bases de datos** → **phpMyAdmin**
   - Selecciona tu base de datos
   - Ve a la pestaña **Importar**
   - Sube el archivo `preguntados.sql`

3. **Prueba el sitio**:
   - `tu-dominio.com/test.php`
   - `tu-dominio.com/index.php`

