<form action="../index.php?action=login" method="POST">
    <h2>Iniciar sesión</h2>
    <input type="text" name="nombre" placeholder="Nombre de usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Ingresar</button>
</form>

<?php
if (isset($_GET['error']) && $_GET['error'] === 'login') {
    echo "<p style='color:red;'>Nombre o contraseña incorrectos.</p>";
}
?>