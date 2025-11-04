# Migración a Mustache Template Engine

## ✅ Implementación Completada

Se ha migrado exitosamente el sistema de plantillas de PHP nativo a **Mustache Template Engine**.

## 📁 Estructura de Archivos

```
preguntadosGame/
├── composer.json              # Configuración de Composer
├── composer.lock             # Lock de dependencias
├── vendor/                   # Dependencias (generado por Composer)
├── views/
│   ├── templates/            # ✨ Nuevas plantillas Mustache
│   │   ├── partials/
│   │   │   ├── header.mustache
│   │   │   └── footer.mustache
│   │   ├── login.mustache
│   │   ├── registro.mustache
│   │   ├── listadoJugadores.mustache
│   │   └── partida.mustache
│   └── (vistas PHP antiguas - pueden eliminarse)
└── helper/
    └── Renderer.php          # ✨ Refactorizado para usar Mustache
```

## 🚀 Instalación Local

```bash
# Instalar dependencias
composer install

# Probar localmente
# Accede a: http://localhost/preguntadosGame/index.php
```

## 🌐 Deployment en Hostinger

### Opción 1: Instalar en el servidor (Recomendado)

1. Sube todos los archivos excepto `vendor/`
2. Conéctate por SSH:
   ```bash
   cd ~/domains/gray-elk-902439.hostingersite.com/public_html
   composer install --no-dev
   ```

### Opción 2: Subir vendor completo

- Sube toda la carpeta `vendor/` al servidor junto con los demás archivos

## 📝 Características de Mustache Implementadas

### Variables
```mustache
{{usuario}}
{{currentYear}}
```

### Secciones Condicionales
```mustache
{{#usuario}}
  <!-- Se muestra si usuario existe -->
{{/usuario}}
```

### Secciones Invertidas
```mustache
{{^jugadores}}
  <!-- Se muestra si jugadores está vacío -->
{{/jugadores}}
```

### Partials (Componentes Reutilizables)
```mustache
{{>partials/header}}
{{>partials/footer}}
```

### Escape Automático
- Todas las variables se escapan automáticamente para prevenir XSS
- Usa `{{{variable}}}` (triple) para HTML sin escapar (si es necesario)

## 🔄 Migración de Vistas

### Antes (PHP):
```php
<?php if (!empty($error)) : ?>
    <div class="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
```

### Después (Mustache):
```mustache
{{#error}}
    <div class="alert">{{error}}</div>
{{/error}}
```

## 📊 Datos Globales Automáticos

El Renderer automáticamente inyecta estos datos en todas las plantillas:
- `usuario` - Usuario logueado (o null)
- `currentYear` - Año actual

## 🎯 Ventajas de Mustache

1. **Separación de Lógica y Presentación**: No hay código PHP en las vistas
2. **Seguridad**: Escape automático de HTML
3. **Reutilización**: Partials para componentes comunes
4. **Mantenibilidad**: Código más limpio y fácil de mantener
5. **Multi-lenguaje**: Mismo motor para PHP, JavaScript, Python, etc.

## ⚠️ Notas Importantes

- Las vistas PHP antiguas en `views/` ya no se usan
- Puedes eliminarlas después de verificar que todo funciona
- El archivo `config/config.ini` debe estar en el servidor (no se sube a Git)

## 🐛 Troubleshooting

### Error: "Mustache no está instalado"
```bash
composer install
```

### Error: "Class 'Mustache_Engine' not found"
- Verifica que `vendor/autoload.php` existe
- Verifica que `index.php` carga el autoloader antes de usar Renderer

### Las plantillas no se renderizan
- Verifica que las plantillas estén en `views/templates/`
- Verifica que tengan extensión `.mustache`
- Verifica permisos de lectura en las carpetas

