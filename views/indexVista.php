<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
?>

<main>
    <div>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>!</h2>
        <p>¿Quieres jugar una partida? Deberas Loguearte o Registrarte primero.</p>
    </div>
</main>