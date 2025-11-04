# Instalación de Mustache Template Engine

## Requisitos Previos

- PHP 7.0 o superior
- Composer instalado

## Instalación

### 1. Instalar Composer (si no lo tienes)

**En Windows:**
- Descarga desde: https://getcomposer.org/download/
- O ejecuta: `php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"`
- Ejecuta: `php composer-setup.php`

**En Linux/Mac:**
```bash
curl -sS https://getcomposer.org/installer | php
```

### 2. Instalar Dependencias

En la raíz del proyecto, ejecuta:

```bash
composer install
```

Esto instalará Mustache y creará la carpeta `vendor/`.

### 3. Estructura de Carpetas

Después de la instalación, deberías tener:

```
preguntadosGame/
├── vendor/              # Dependencias de Composer (generado)
├── views/
│   ├── templates/       # Plantillas Mustache
│   │   ├── partials/    # Partials (header, footer)
│   │   ├── login.mustache
│   │   ├── registro.mustache
│   │   ├── listadoJugadores.mustache
│   │   └── partida.mustache
│   └── ...              # Vistas PHP antiguas (puedes mantenerlas como backup)
```

### 4. Verificar Instalación

Abre tu navegador y accede a:
- `http://localhost/preguntadosGame/index.php`

Deberías ver el sitio funcionando con Mustache.

## Para Deployment en Hostinger

### Opción 1: Subir vendor/ completo
- Sube toda la carpeta `vendor/` al servidor

### Opción 2: Instalar en el servidor (Recomendado)
1. Sube todos los archivos excepto `vendor/`
2. Conéctate por SSH a Hostinger
3. Navega al directorio del proyecto
4. Ejecuta: `composer install --no-dev`

## Características de Mustache Implementadas

- ✅ Partials (header, footer)
- ✅ Variables ({{variable}})
- ✅ Secciones condicionales ({{#variable}}...{{/variable}})
- ✅ Secciones invertidas ({{^variable}}...{{/variable}})
- ✅ Escape automático de HTML (XSS protection)
- ✅ Datos globales (usuario, currentYear)

## Migración de Vistas PHP a Mustache

Las vistas PHP antiguas están en `views/` pero ya no se usan.
Las nuevas plantillas Mustache están en `views/templates/`.

Puedes eliminar las vistas PHP antiguas si todo funciona correctamente.

