# Instrucciones de Deployment en Hostinger

## Problema de Ramas Divergentes

Si Hostinger muestra el error:
```
fatal: Need to specify how to reconcile divergent branches.
```

### Solución 1: Configurar Git en tu repositorio local

Ejecuta estos comandos en tu terminal local:

```bash
# Configurar estrategia de merge para el repositorio
git config pull.rebase false

# O usar merge como estrategia global
git config --global pull.rebase false

# Hacer commit y push
git add .
git commit -m "Configurar deployment"
git push origin main
```

### Solución 2: Forzar merge en Hostinger

Si no puedes cambiar la configuración local, puedes editar el archivo `.git/config` en Hostinger (si tienes acceso SSH) o contactar a soporte de Hostinger para que configuren:

```
[pull]
    rebase = false
```

### Solución 3: Usar Deployment Manual

1. Sube los archivos manualmente por FTP o File Manager
2. Asegúrate de crear `config/config.ini` con las credenciales de Hostinger

## Configuración de config.ini

**IMPORTANTE**: El archivo `config/config.ini` NO debe subirse a Git (está en .gitignore).

Después del deployment, crea manualmente en Hostinger el archivo `config/config.ini`:

```ini
server = "localhost"
username = "tu_usuario_hostinger"
password = "tu_contraseña_hostinger"
db_name = "tu_base_de_datos_hostinger"
```

## Verificación Post-Deployment

1. Accede a `tu-dominio.com/test.php` para verificar que todo funciona
2. Verifica que `config/config.ini` existe y tiene las credenciales correctas
3. Revisa los logs de error si hay problemas

