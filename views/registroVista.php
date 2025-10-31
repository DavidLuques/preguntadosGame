<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
            <?php if (isset($_GET['success']) && $_GET['success'] === 'registro'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Usuario registrado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h1 class="h4 fw-bold mb-1">Crear cuenta</h1>
                        <p class="text-muted mb-0">Registrate para jugar</p>
                    </div>

                    <form action="../index.php?request=registrar" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
                            <div class="invalid-feedback">Ingresá un usuario.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            <div class="invalid-feedback">Ingresá una contraseña.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Registrarme</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">¿Ya tenés cuenta? <a href="index.php?request=login" class="text-decoration-underline">Iniciá sesión</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>