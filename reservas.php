<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'comunidad_educativa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ruta de la carpeta para guardar los materiales
    $upload_dir = "uploads/";

    // Crear la carpeta 'uploads' si no existe
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Manejo de subida de materiales
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['material_file'])) {
        $material_name = htmlspecialchars($_POST['material_name']);
        $material_type = htmlspecialchars($_POST['material_type']);
        $file = $_FILES['material_file'];

        if ($file['error'] === 0) {
            $file_name = basename($file['name']);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Guardar en la base de datos
                $stmt = $pdo->prepare("INSERT INTO materiales (nombre, tipo, ruta, fecha) VALUES (:nombre, :tipo, :ruta, NOW())");
                $stmt->execute([
                    'nombre' => $material_name,
                    'tipo' => $material_type,
                    'ruta' => $target_file
                ]);

                echo "Material subido exitosamente.<br>";
            } else {
                echo "Error al mover el archivo al directorio de destino.<br>";
            }
        } else {
            echo "Error al subir el archivo.<br>";
        }
    }

    // Obtener materiales desde la base de datos para mostrarlos
    $stmt = $pdo->query("SELECT * FROM materiales ORDER BY fecha DESC");
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Materiales</title>
</head>
<body>
    <h1>Gestión de Materiales</h1>
    
    <!-- Formulario para subir materiales -->
    <h2>Subir Material</h2>
    <form action="subir_materiales.php" method="POST" enctype="multipart/form-data">
        <label for="material_name">Nombre del Material:</label>
        <input type="text" name="material_name" id="material_name" required><br><br>
        
        <label for="material_type">Tipo de Material:</label>
        <select name="material_type" id="material_type" required>
            <option value="PDF">PDF</option>
            <option value="Video">Video</option>
            <option value="Imagen">Imagen</option>
            <option value="Texto">Texto</option>
        </select><br><br>
        
        <label for="material_file">Archivo:</label>
        <input type="file" name="material_file" id="material_file" required><br><br>
        
        <button type="submit">Subir Material</button>
    </form>
    
    <!-- Listado de materiales con opción de descarga -->
    <h2>Materiales Disponibles</h2>
    <?php if (!empty($materiales)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materiales as $material): ?>
                    <tr>
                        <td><?= htmlspecialchars($material['nombre']) ?></td>
                        <td><?= htmlspecialchars($material['tipo']) ?></td>
                        <td><?= htmlspecialchars($material['fecha']) ?></td>
                        <td><a href="<?= htmlspecialchars($material['ruta']) ?>" download>Descargar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay materiales disponibles.</p>
    <?php endif; ?>
</body>
</html>
