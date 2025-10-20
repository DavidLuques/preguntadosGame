<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #3b82f6 0%, #9333ea 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: none;
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
                <?php if (isset($_GET['error']) && $_GET['error'] === 'login'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Nombre o contraseña incorrectos.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body p-4 p-sm-5">
                        <div class="text-center mb-4">
                            <h1 class="h4 fw-bold mb-1">Preguntados</h1>
                            <p class="text-muted mb-0">Iniciá sesión para continuar</p>
                        </div>

                        <form action="../index.php?action=login" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
                                <div class="invalid-feedback">Ingresá tu usuario.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                <div class="invalid-feedback">Ingresá tu contraseña.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">¿No tenés cuenta? <a href="../views/registro.php" class="text-decoration-underline">Registrate</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHzC9" crossorigin="anonymous"></script>
    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
