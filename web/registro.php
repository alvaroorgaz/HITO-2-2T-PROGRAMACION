<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <div class="container" >
        <h2>Registro de Usuario</h2>

        <?php
        require_once "bbdd.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre_usuario = trim($_POST["nombre_usuario"]);
            $correo = trim($_POST["correo"]);
            $contrasena = trim($_POST["contrasena"]);
            $acepta_politicas = isset($_POST["acepta_politicas"]); // Verificar si el checkbox está marcado

            if (empty($nombre_usuario) || empty($correo) || empty($contrasena)) {
                echo "<p>Todos los campos son obligatorios.</p>";
            } elseif (!$acepta_politicas) {
                echo "<p>Debes aceptar las políticas para registrarte.</p>";
            } else {
                // Verificar que el correo sea único
                $sql = "SELECT id FROM usuarios WHERE correo = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo "<p class='label-form'>El correo ya está registrado.</p>";
                } else {
                    // Insertar nuevo usuario
                    $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $nombre_usuario, $correo, $hash_contrasena);

                    if ($stmt->execute()) {
                        echo "<p>Registro exitoso. <a href='login.php'>Iniciar sesión</a></p>";
                    } else {
                        echo "<p>Error en el registro.</p>";
                    }
                }
                $stmt->close();
            }
            $conn->close();
        }
        ?>

        <form action="registro.php" method="POST" >
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <label>
                <input type="checkbox" name="acepta_politicas" required>
                Acepto las <a href="politicas.php" target="_blank" style="color: #007BFF">políticas de privacidad</a>
            </label>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
