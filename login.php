<?php
// login.php
session_start(); 
include 'db_config.php'; 

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rolNombre = $_POST['Usuario'];
    $password = $_POST['Password']; 

    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta para verificar el rol y la contraseña
    $stmt = $conn->prepare("SELECT idrol, nombre FROM rol WHERE nombre = :rolNombre AND contraseña = :password");
    $stmt->bindParam(':rolNombre', $rolNombre);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Si el rol existe
        $rol = $stmt->fetch(PDO::FETCH_ASSOC);

        // Guardar datos en la sesión
        $_SESSION['role_id'] = $rol['idrol'];
        $_SESSION['role_name'] = $rol['nombre'];

        // Redireccionar según el rol
        if ($rolNombre == 'admin') {
            header('Location: menu_admin.php');
            exit();
        } elseif ($rolNombre == 'empleado') {
            header('Location: menu_admin.php');
            exit();
        } elseif ($rolNombre == 'encargado') {
            header('Location: menu_admin.php');
            exit();
        } else {
            // Si el rol no es reconocido
            header('Location: login.php?error=rol_desconocido');
            exit();
        }
    } else {
        // Si las credenciales son incorrectas
        header('Location: login.php?error=credenciales_incorrectas');
        exit();
    }
}
?>
<!-- Este documento HTML define una página de inicio de sesión.
     - Incluye metadatos estándar en el encabezado como el conjunto de caracteres, el viewport y el título de la página.
     - Enlaza a una hoja de estilos CSS externa para el diseño de la página.
     - Contiene un formulario de inicio de sesión en el cuerpo, con un diseño centrado y elementos gráficos.
       - Un encabezado 'h1' y un contenedor con un ícono de usuario.
       - El formulario solicita el nombre de usuario y la contraseña, ambos campos obligatorios, y envía los datos a 'login.php'.
       - Incluye un enlace para la recuperación de la contraseña. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   
    <link rel="stylesheet" href="assets/css/estilos_login.css">
</head>

<body>
   
    <div class="login-container">
        <h1>Venfarma</h1>
        <div class="icon-container">
            <img src="/img/baseline_account_circle_black_48dp.png" alt="Ícono de usuario">
        </div>
        <form action="login.php" method="post">
            <input type="text" name="Usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="Password" placeholder="Contraseña" required>
            <button type="submit" name="login">Iniciar sesión</button>
        </form>
        <a href="path_to_recovery_page">Recuperar Contraseña</a>
    </div>
</body>
</html>
