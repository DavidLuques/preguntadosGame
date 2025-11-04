# Solución para Problemas Visuales con Mustache

## Problemas Comunes y Soluciones

### 1. Partials no se cargan

**Síntoma:** Los partials (header, footer) no se muestran o aparecen como texto plano.

**Solución:** Verifica que:
- Los archivos estén en `views/templates/partials/`
- Los archivos tengan extensión `.mustache`
- Los partials se llamen como `{{>partials/header}}` en las plantillas

### 2. Variables no se muestran

**Síntoma:** Las variables aparecen como `{{variable}}` en lugar de su valor.

**Causa:** Los datos no se están pasando correctamente o la variable no existe.

**Solución:** 
- Verifica que los datos se pasen en el controlador
- Asegúrate de que las claves de los arrays coincidan con las variables en Mustache

### 3. Estilos CSS no se cargan

**Síntoma:** La página se ve sin estilos.

**Solución:**
- Verifica que Bootstrap se cargue en el header
- Verifica que los estilos inline en `<style>` estén presentes
- Revisa la consola del navegador para errores 404

### 4. Datos de arrays no se iteran

**Síntoma:** Los jugadores no se muestran en la tabla.

**Solución:**
- Verifica que `jugadores` sea un array
- Verifica que el array no esté vacío
- Usa `{{#jugadores}}...{{/jugadores}}` para iterar

## Debugging

Agrega esto temporalmente en `Renderer.php` para ver qué datos se pasan:

```php
public function render($template, $data = []) {
    // DEBUG (temporal)
    error_log("Template: " . $template);
    error_log("Data: " . print_r($data, true));
    
    // ... resto del código
}
```

Luego revisa los logs de error de PHP.

