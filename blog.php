<?php
session_start();

// Conectar a la base de datos
$host = 'localhost';
$dbname = 'comunidad_educativa';
$username = 'root';
$password = ''; // Cambia según tu configuración

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $usuario_id = $_POST['usuario_id'];
    
    $stmt = $conn->prepare("INSERT INTO blogs (titulo, contenido, usuario_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $titulo, $contenido, $usuario_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT b.titulo, b.contenido, b.fecha, u.nombre FROM blogs b JOIN usuarios u ON b.usuario_id = u.id ORDER BY b.fecha DESC");
$blogs = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($blogs);
$conn->close();
?>
