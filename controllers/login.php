<?php

if (isset($_POST["usuario"]) && isset($_POST["password"])) {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
    $resultado = $conexion->query($sql);
    if (sizeof($resultado) > 0) {
        // Inicio de sesión exitoso
        $_SESSION["usuario"] = $usuario;
        header("Location: index.php");
        echo "<p style='color:green;'>Inicio de sesión exitoso.</p>";
        exit();
    } else {
        // Error de inicio de sesión
        $error = "Nombre o contraseña incorrectos.";

    }
}

include_once ("../views/partials/header.php");
include_once ("../views/loginVista.php");
include_once ("../views/partials/footer.php");
?>