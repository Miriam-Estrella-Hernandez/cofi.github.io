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

    // Verifica si los datos del formulario están definidos
    if (isset($_POST['correo']) && isset($_POST['password'])) {
        $correo = $_POST['correo'];
        $password = $_POST['password'];

        // Consulta para verificar el correo
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificación de la contraseña
        if ($user && password_verify($password, $user['contrasena'])) {
            // Autenticación exitosa: Guardar datos en la sesión
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];
            
            // Redirigir según el rol del usuario
            if ($user['rol'] == 'admin') {
                header("Location: admin.php"); // Redirigir a página de administrador
            } elseif ($user['rol'] == 'profesor') {
                header("Location: profesor.php"); // Redirigir a página de profesor
            } else {
                header("Location: alumnos.php"); // Redirigir a página de alumno
            }
            exit();
        } else {
            echo "Correo o contraseña incorrectos.";
        }
    } else {
        echo "Por favor, complete el formulario de login.";
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
