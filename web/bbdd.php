<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "1234";
$base_datos = "gestion_tareas";

// Crear conexión
$conn = new mysqli($servidor, $usuario_db, $contrasena_db, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar el conjunto de caracteres
$conn->set_charset("utf8");

?>
