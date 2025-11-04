# Solución para el Error de Deployment en Hostinger

## Problema
Hostinger muestra: `fatal: Need to specify how to reconcile divergent branches.`

## Solución 1: Configurar Git vía SSH (Recomendado)

Si tienes acceso SSH a tu cuenta de Hostinger:

1. Conéctate por SSH a tu servidor
2. Navega al directorio de tu proyecto
3. Ejecuta estos comandos:

```bash
cd /home/usuario/dominio/public_html  # Ajusta la ruta según tu configuración
git config pull.rebase false
git pull origin main
```

## Solución 2: Editar .git/config en Hostinger

1. Accede al **File Manager** de Hostinger
2. Ve a la carpeta raíz de tu proyecto (donde está el `.git`)
3. Edita el archivo `.git/config` (debe estar oculto, activa "Mostrar archivos ocultos")
4. Agrega esta sección si no existe:

```ini
[pull]
    rebase = false
```

5. Guarda el archivo
6. Intenta el deployment nuevamente

## Solución 3: Usar Deployment Manual

Si el auto-deployment no funciona:

1. **Desactiva el auto-deployment** en el panel de Hostinger
2. **Sube los archivos manualmente**:
   - Por FTP usando FileZilla o similar
   - O por el File Manager de Hostinger
3. Asegúrate de subir TODOS los archivos excepto:
   - `.git/` (carpeta completa)
   - `config/config.ini` (crea este archivo manualmente en el servidor)

## Solución 4: Resetear el Repositorio en Hostinger

Si tienes acceso SSH:

```bash
cd /ruta/a/tu/proyecto
git fetch origin
git reset --hard origin/main
```

## Verificación Post-Deployment

Después de solucionar el deployment:

1. **Crea el archivo `config/config.ini`** en Hostinger con:
   ```ini
   server = "localhost"
   username = "u625329211_preguntados"
   password = "+MpWww?d$5P"
   db_name = "u625329211_preguntados"
   ```

2. **Importa la base de datos**:
   - Ve a phpMyAdmin en Hostinger
   - Importa el archivo `preguntados.sql`

3. **Prueba el sitio**:
   - Accede a `tu-dominio.com/test.php` primero
   - Luego a `tu-dominio.com/index.php`

## Contactar Soporte de Hostinger

Si ninguna solución funciona, contacta al soporte de Hostinger y pídeles que:
- Configuren `git config pull.rebase false` en tu repositorio
- O que cambien el comando de deployment para usar `git pull --no-rebase`

