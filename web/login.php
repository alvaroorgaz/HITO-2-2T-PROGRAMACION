<?php
// Incluir la conexión a la base de datos
require_once "bbdd.php";
session_start(); // Iniciar sesión

$mensaje = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    // Validar que los campos no estén vacíos
    if (empty($correo) || empty($contrasena)) {
        $mensaje = "Por favor, completa todos los campos.";
    } else {
        // Consultar usuario por correo
        $sql = "SELECT id, nombre_usuario, contrasena FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        // Verificar si se encontró un usuario
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nombre_usuario, $contrasena_hash);
            $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($contrasena, $contrasena_hash)) {
                // Iniciar sesión y guardar datos del usuario
                $_SESSION["usuario_id"] = $id;
                $_SESSION["nombre_usuario"] = $nombre_usuario;
                header("Location: tareas.php"); // Redirigir a la página de tareas
                exit();
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            $mensaje = "No existe una cuenta con ese correo.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <h2>Iniciar Sesión</h2>
    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>
    <div class="container">
        <form action="login.php" method="POST">
            <label for="correo" class="label-form">Correo Electrónico:</label>
            <input type="email" name="correo" required>
            <br>

            <label for="contrasena" class="label-form">Contraseña:</label>
            <input type="password" name="contrasena" required>
            <br>

            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <p class="label-form">¿No tienes cuenta? <a href="registro.php" style="color: #007BFF">Regístrate aquí</a></p>
    </div>
</body>
</html>
