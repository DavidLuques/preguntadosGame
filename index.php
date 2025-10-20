<?php
require_once 'controllers/UsuarioController.php';

$controller = new UsuarioController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registrar':
            $controller->registrar($_POST['nombre'], $_POST['password']);
            exit;
        case 'login':
            $controller->login($_POST['nombre'], $_POST['password']);
            exit;
    }
}

if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntados - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Preguntados</a>
            <div class="ms-auto">
                <a href="views/login.php" class="btn btn-outline-light btn-sm me-2">Iniciar sesión</a>
                <a href="views/registro.php" class="btn btn-primary btn-sm">Registrarse</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registro'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Usuario registrado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'login'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Nombre o contraseña incorrectos.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row align-items-center py-5">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-3">Bienvenido a Preguntados</h1>
                <p class="lead text-muted">Creá tu cuenta o iniciá sesión para comenzar a jugar.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="views/login.php" class="btn btn-primary btn-lg">Iniciar sesión</a>
                    <a href="views/registro.php" class="btn btn-outline-secondary btn-lg">Registrarse</a>
                </div>
            </div>
            <div class="col-md-6 text-center d-none d-md-block">
                <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f4da.svg" alt="libros" width="200" height="200">
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHzC9" crossorigin="anonymous"></script>
</body>
</html>
