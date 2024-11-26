<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$dbname = 'comunidad_educativa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Subir un comentario al chat
    if (isset($_POST['chat_message'])) {
        $message = $_POST['chat_message'];
        $usuario = $_SESSION['usuario'];

       $stmt = $pdo->prepare("INSERT INTO chat (usuario, mensaje, fecha) VALUES (:usuario, :mensaje, NOW())");
       $stmt->execute(['usuario' => $usuario, 'mensaje' => $message]);
    }

    // Subir un artículo al blog
    //if (isset($_POST['blog_title']) && isset($_POST['blog_content'])) {
    //    $title = $_POST['blog_title'];
    //    $content = $_POST['blog_content'];
    //    $stmt = $pdo->prepare("INSERT INTO blog (titulo, contenido, fecha) VALUES (:titulo, :contenido, NOW())");
    //    $stmt->execute(['titulo' => $title, 'contenido' => $content]);
    //}

    // Subir un material de apoyo
    //if (isset($_POST['material_name']) && isset($_POST['material_type']) && isset($_FILES['material_file'])) {
    //    $material_name = $_POST['material_name'];
    //    $material_type = $_POST['material_type'];
    //    $file = $_FILES['material_file'];
    //    $target_dir = "uploads/";
    //    $target_file = $target_dir . basename($file["name"]);
    //    move_uploaded_file($file["tmp_name"], $target_file);

    //    $stmt = $pdo->prepare("INSERT INTO materiales (nombre, tipo, ruta, fecha) VALUES (:nombre, :tipo, :ruta, NOW())");
    //    $stmt->execute(['nombre' => $material_name, 'tipo' => $material_type, 'ruta' => $target_file]);
    //}

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad Educativa - Alumno Tpsi</title>
    <style>
        /* Fondo general de la página con imagen o color */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('https://th.bing.com/th/id/OIP.jvWyCIlHZhhZ7T8NL_ZzSQHaEc?w=297&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7') no-repeat center center fixed;
            background-size: cover;
        }

        /* Contenedor principal centrado */
        .main-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: white; /* Color de fondo del contenedor */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            height: 90%;
        }

        /* Animación de bienvenida */
        #welcome-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            overflow: hidden;
        }

        #welcome-animation img {
            width: 50%;
            max-width: 500px;
            height: auto;
        }

        #welcome-animation p {
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }

        /* Desaparecer bienvenida */
        .fade-out {
            animation: fadeOut 1s forwards;
            visibility: hidden;
            opacity: 0;
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        nav {
            background-color: #4CAF50;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
        }

        nav ul li a.active {
            background-color: #333;
            border-radius: 5px;
        }

        .tab-content {
            display: none;
            padding: 20px;
        }

        .tab-content.active {
            display: block;
        }

    </style>
</head>
<body>
    <div class="main-container">
        <!-- Bienvenida con GIF -->
    <div id="welcome-animation">
        <img src="202411061355.gif" alt="Bienvenido">
        <p>¡Bienvenido a la Comunidad Educativa!</p>
    </div>

    <h1>Comunidad Educativa - Alumno</h1>

    <nav>
        <ul>
            <li><a href="#" onclick="openTab(event, 'chat')" class="tab-link active">Chat</a></li>
            <li><a href="#" onclick="openTab(event, 'blog')" class="tab-link">Blog</a></li>
            <li><a href="#" onclick="openTab(event, 'materiales')" class="tab-link">Materiales</a></li>
            <li><a href="logout.php" class="tab-link">Salir</a></li>
        </ul>
    </nav>

    <!-- Sección de Chat -->
    <section id="chat" class="tab-content active">
        <h2>Chat en tiempo real</h2>
        <div id="chat-box">
            <?php
            $stmt = $pdo->query("SELECT * FROM chat ORDER BY fecha DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<p><strong>" . htmlspecialchars($row['usuario']) . ":</strong> " . htmlspecialchars($row['mensaje']) . "</p>";
            }
            ?>
        </div>
        <form action="alumnos.php" method="POST">
            <textarea name="chat_message" required placeholder="Escribe un comentario..."></textarea>
            <button type="submit">Enviar</button>
        </form>
    </section>

    <!-- Sección de Blog -->
    <section id="blog" class="tab-content"> 
        <h2>Blog de Recursos</h2> 
        <div id="blog-content"> <?php $stmt = $pdo->query("SELECT * FROM blog ORDER BY fecha DESC"); while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            echo "<h3>" . htmlspecialchars($row['titulo']) . "</h3><p>" . nl2br(htmlspecialchars($row['contenido'])) . "</p>"; } ?> 
        </div> <!-- Formulario de blog comentado para deshabilitar la subida de artículos --> <!-- <form action="alumnos.php" method="POST"> <input type="text" name="blog_title" required placeholder="Título del artículo"> <textarea name="blog_content" required placeholder="Escribe el contenido del artículo..."></textarea> <button type="submit">Publicar Artículo</button> </form> --> 
    </section>

    <!-- Sección de Materiales -->
    <section id="materiales" class="tab-content"> <h2>Materiales de Apoyo</h2> <div id="materiales-content"> <?php $stmt = $pdo->query("SELECT * FROM materiales ORDER BY fecha DESC"); while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { echo "<p><strong>" . htmlspecialchars($row['nombre']) . " (" . htmlspecialchars($row['tipo']) . ")</strong> <a href='" . htmlspecialchars($row['ruta']) . "' target='_blank'>Descargar</a></p>"; } ?> </div> <!-- Formulario deshabilitado --> <form action="alumnos.php" method="POST" enctype="multipart/form-data" style="display: none;"> <input type="text" name="material_name" required placeholder="Nombre del material"> <select name="material_type" required> <option value="PDF">PDF</option> <option value="Video">Video</option> <option value="Imagen">Imagen</option> <option value="Texto">Texto</option> </select> <input type="file" name="material_file" required> <button type="submit">Subir Material</button> </form> </section>
    <script>
        // Función para ocultar el GIF de bienvenida después de 3 segundos
        window.onload = function() {
            setTimeout(function() {
                document.getElementById('welcome-animation').classList.add('fade-out');
            }, 3000);
        };

        // Función para cambiar entre pestañas
        function openTab(event, tabName) {
            const sections = document.querySelectorAll('.tab-content');
            const links = document.querySelectorAll('.tab-link');

            sections.forEach(section => section.classList.remove('active'));
            links.forEach(link => link.classList.remove('active'));

            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>

    </div>
</body>
</html>
