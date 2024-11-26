<?php
session_start();

// Conectar a la base de datos
$host = 'localhost';
$dbname = 'comunidad_educativa';
$username = 'root';
$password = ''; // Cambia según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si los datos del formulario están definidos
    if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['password']) && isset($_POST['rol'])) {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $password = $_POST['password'];
        $rol = $_POST['rol'];

        // Verificar si el correo ya está registrado
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Este correo ya está registrado. Por favor, usa otro.";
        } else {
            // Hashear la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) 
                                   VALUES (:nombre, :correo, :contrasena, :rol)");
            $stmt->execute([
                'nombre' => $nombre,
                'correo' => $correo,
                'contrasena' => $hashed_password,
                'rol' => $rol
            ]);

            echo "Registro exitoso. Ahora puedes <a href='index.html'>iniciar sesión</a>.";
        }
    } else {
        echo "Por favor, complete todos los campos del formulario.";
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
