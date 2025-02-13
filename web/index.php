<?php
session_start();

// Si el usuario ya está autenticado, redirigir a la página de tareas
if (isset($_SESSION["usuario_id"])) {
    header("Location: tareas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <h1>Bienvenido a la Gestión de Tareas</h1>
    <p>Organiza tus pendientes de manera sencilla.</p>

    <div class="container">
    <h2>Acceder</h2>
    <p><button><a href="login.php">Iniciar Sesión</a></button> | <button><a href="registro.php" >Registrarse</a></button></p></div>
</body>
</html>
