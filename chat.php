<?php
session_start();

// Conectar a la base de datos
$host = 'localhost';
$dbname = 'comunidad_educativa';
$username = 'root';
$password = ''; // Cambia según tu configuración

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = $_POST['mensaje'];
    $usuario_id = $_POST['usuario_id']; // ID del usuario que envía el mensaje
    
    $stmt = $conn->prepare("INSERT INTO mensajes (usuario_id, mensaje) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $mensaje);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT m.mensaje, u.nombre, m.fecha FROM mensajes m JOIN usuarios u ON m.usuario_id = u.id ORDER BY m.fecha DESC");
$mensajes = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($mensajes);
$conn->close();
?>
