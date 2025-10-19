<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>!</h2>

<?php if (isset($_GET['bienvenido'])): ?>
    <p style="color:green;">Inicio de sesión exitoso 😊</p>
<?php endif; ?>

<a href="../index.php?logout=true">Cerrar sesión</a>