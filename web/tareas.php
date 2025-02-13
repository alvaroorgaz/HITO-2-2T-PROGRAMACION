<?php
// Incluir la conexión a la base de datos
require_once "bbdd.php";
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";

// Agregar una nueva tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nueva_tarea"])) {
    $descripcion = trim($_POST["nueva_tarea"]);

    if (!empty($descripcion)) {
        $sql = "INSERT INTO tareas (usuario_id, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $descripcion);

        if ($stmt->execute()) {
            $mensaje = "Tarea agregada correctamente.";
        } else {
            $mensaje = "Error al agregar la tarea.";
        }
        $stmt->close();
    } else {
        $mensaje = "La descripción de la tarea no puede estar vacía.";
    }
}

// Marcar tarea como completada
if (isset($_GET["completar"])) {
    $tarea_id = $_GET["completar"];
    $sql = "UPDATE tareas SET estado = 'completada' WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $stmt->close();
    header("Location: tareas.php");
    exit();
}

// Eliminar una tarea
if (isset($_GET["eliminar"])) {
    $tarea_id = $_GET["eliminar"];
    $sql = "DELETE FROM tareas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $stmt->close();
    header("Location: tareas.php");
    exit();
}

// Obtener las tareas del usuario
$sql = "SELECT id, descripcion, estado FROM tareas WHERE usuario_id = ? ORDER BY fecha_creacion DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$tareas = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
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

    <h2>Gestión de Tareas</h2>
    <p>Bienvenido, <?php echo $_SESSION["nombre_usuario"]; ?> | <a href="logout.php">Cerrar sesión</a></p>

    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

    <h3>Agregar Nueva Tarea</h3>
    <form action="tareas.php" method="POST">
        <input type="text" name="nueva_tarea" required>
        <button type="submit">Agregar</button>
    </form>
    <br>
    <h3>Mis Tareas</h3>
    <br>
    <ul>
        <?php foreach ($tareas as $tarea): ?>
            <li class="label-form">
                <?php echo htmlspecialchars($tarea["descripcion"]); ?> 
                (<?php echo $tarea["estado"]; ?>)
                <?php if ($tarea["estado"] == "pendiente"): ?>
                    <a style="color: #007BFF" href="?completar=<?php echo $tarea["id"]; ?>">[Completar]</a>
                <?php endif; ?>
                <a style="color: #007BFF" href="?eliminar=<?php echo $tarea["id"]; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta tarea?');">[Eliminar]</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
