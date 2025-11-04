<?php
// Archivo de debug para Mustache
require_once __DIR__ . '/vendor/autoload.php';

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

$templatePath = __DIR__ . '/views/templates';

echo "<h1>Debug Mustache</h1>";

echo "<h2>1. Verificar archivos</h2>";
$files = [
    'header' => $templatePath . '/partials/header.mustache',
    'footer' => $templatePath . '/partials/footer.mustache',
    'login' => $templatePath . '/login.mustache',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "✓ $name existe: " . $path . "<br>";
    } else {
        echo "✗ $name NO existe: " . $path . "<br>";
    }
}

echo "<h2>2. Probar Mustache</h2>";
try {
    $loader = new Mustache_Loader_FilesystemLoader($templatePath, ['extension' => '.mustache']);
    $mustache = new Mustache_Engine([
        'loader' => $loader,
        'partials_loader' => $loader,
    ]);
    
    echo "✓ Mustache Engine creado correctamente<br>";
    
    // Probar renderizar header
    $data = ['usuario' => false, 'currentYear' => date('Y')];
    $header = $mustache->render('partials/header', $data);
    
    echo "<h3>Header renderizado:</h3>";
    echo "<pre>" . htmlspecialchars(substr($header, 0, 500)) . "...</pre>";
    
    // Probar renderizar login
    $loginData = array_merge($data, ['error' => null]);
    $login = $mustache->render('login', $loginData);
    
    echo "<h3>Login renderizado (primeros 500 chars):</h3>";
    echo "<pre>" . htmlspecialchars(substr($login, 0, 500)) . "...</pre>";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

