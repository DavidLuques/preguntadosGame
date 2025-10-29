<div class="container">
    <form action="../index.php?request=login" method="POST">
        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)) {
            echo "<div class='container'> . $error . </div>";
        } ?>
        <p><input type="text" name="nombre" placeholder="Nombre de usuario" required></p>
        <p><input type="password" name="password" placeholder="Contraseña" required></p>
        <p><button type="submit">Ingresar</button></p>
    </form>
</div>