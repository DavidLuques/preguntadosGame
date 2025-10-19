<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit;
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']['nombre']; ?>!</h2>
<a href="../../index.php?logout=true">Cerrar sesión</a>