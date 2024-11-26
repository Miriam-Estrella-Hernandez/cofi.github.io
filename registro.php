<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Comunidad Educativa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="welcome-animation" class="fade-in">
        <img src="202411061355.gif" alt="Robot saludando" class="robot">
        <h2>¡Bienvenido a la Comunidad Educativa!</h2>
    </div>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="registro_procesar.php" method="POST" class="login-form">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                <option value="alumno">Alumno</option>
                <option value="profesor">Profesor</option>
                <option value="administrador">Administrador</option>
            </select>
            
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="index.html">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
