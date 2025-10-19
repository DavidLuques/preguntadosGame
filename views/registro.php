<form action="../index.php?action=registrar" method="POST">
    <h2>Registro</h2>
    <input type="text" name="nombre" placeholder="Nombre de usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Registrar</button>
</form>

<?php
if (isset($_GET['success']) && $_GET['success'] === 'registro') {
    echo "<p style='color:green;'>Usuario registrado correctamente.</p>";
}
?>