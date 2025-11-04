# Cómo Conectarte por SSH a Hostinger

## Tus Credenciales SSH
- **IP**: 82.25.73.89
- **Puerto**: 65002
- **Usuario**: u625329211
- **Contraseña**: (la que configuraste en Hostinger)

---

## Opción 1: Usar PowerShell (Windows 10/11) - RECOMENDADO

### Paso 1: Abrir PowerShell
1. Presiona `Windows + X`
2. Selecciona "Windows PowerShell" o "Terminal"

### Paso 2: Conectarte
```powershell
ssh u625329211@82.25.73.89 -p 65002
```

### Paso 3: Ingresar contraseña
- Te pedirá la contraseña (no se verá mientras escribes, es normal)
- Presiona Enter

### Paso 4: Configurar Git
Una vez conectado, ejecuta estos comandos:

```bash
# Navegar al directorio del proyecto
cd public_html

# O si tu proyecto está en otra ubicación, encuentra la ruta:
# pwd  # para ver dónde estás
# ls   # para ver qué hay en el directorio actual

# Configurar Git para usar merge
git config pull.rebase false

# Verificar que se configuró
git config --get pull.rebase
# Debe mostrar: false

# Salir de SSH
exit
```

---

## Opción 2: Usar PuTTY (Alternativa)

### Paso 1: Descargar PuTTY
- Descarga desde: https://www.putty.org/
- O instala desde Microsoft Store

### Paso 2: Configurar PuTTY
1. Abre PuTTY
2. En "Host Name (or IP address)": `82.25.73.89`
3. En "Port": `65002`
4. En "Connection type": selecciona **SSH**
5. Haz clic en **Open**

### Paso 3: Ingresar credenciales
1. Te pedirá usuario: `u625329211`
2. Te pedirá contraseña: (ingresa la tuya)
3. Presiona Enter

### Paso 4: Configurar Git
Una vez conectado, ejecuta los mismos comandos que en la Opción 1.

---

## Opción 3: Usar Git Bash (Si tienes Git instalado)

1. Abre **Git Bash**
2. Ejecuta:
```bash
ssh u625329211@82.25.73.89 -p 65002
```
3. Ingresa la contraseña
4. Ejecuta los comandos de configuración de Git

---

## Comandos Útiles una vez conectado

```bash
# Ver dónde estás
pwd

# Ver archivos en el directorio actual
ls

# Ver archivos ocultos también
ls -la

# Navegar a una carpeta
cd public_html

# Ver el contenido de .git/config
cat .git/config

# Editar .git/config (si es necesario)
nano .git/config
# Presiona Ctrl+X para salir, luego Y para guardar, Enter para confirmar

# Configurar Git
git config pull.rebase false

# Verificar configuración
git config --get pull.rebase
```

---

## Solución de Problemas

### Error: "Connection refused" o "Connection timed out"
- Verifica que el puerto 65002 esté correcto
- Asegúrate de que SSH esté habilitado en Hostinger

### Error: "Permission denied"
- Verifica que el usuario y contraseña sean correctos
- La contraseña no se muestra mientras escribes (es normal)

### No encuentro el directorio del proyecto
```bash
# Buscar el directorio .git
find . -name ".git" -type d

# O buscar archivos de tu proyecto
find . -name "index.php" -type f
```

---

## Después de Configurar Git

1. **Sal de SSH**: escribe `exit` y presiona Enter
2. **Ve al panel de Hostinger** → **GIT** → **Gestionar repositorios**
3. **Haz clic en "Implementar"** junto a tu repositorio
4. Debería funcionar sin el error de "divergent branches"

