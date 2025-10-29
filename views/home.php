<?php
session_start();
if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Preguntados</a>
            <div class="d-flex ms-auto">
                <span class="navbar-text me-3">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
                <a href="../index.php?logout=true" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <?php if (isset($_GET['bienvenido'])): ?>
            <div class="alert alert-success" role="alert">Inicio de sesión exitoso</div>
        <?php endif; ?>

        <div class="text-center py-5">
            <h1 class="display-6 mb-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?> 👋</h1>
            <p class="text-muted mb-4">Pronto verás aquí tu tablero de juego.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button" disabled>Comenzar a jugar (próximamente)</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHzC9" crossorigin="anonymous"></script>
</body>
</html>
