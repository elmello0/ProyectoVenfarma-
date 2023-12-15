<!-- Este script PHP gestiona el proceso de inicio de sesión.
     - Incluye un archivo de conexión a la base de datos y comienza o reanuda una sesión.
     - Verifica si se ha enviado el formulario de login.
     - Recolecta el nombre de usuario y la contraseña del formulario.
     - Prepara una consulta SQL para verificar estas credenciales en la base de datos.
     - Ejecuta la consulta y obtiene el resultado.
     - Si encuentra una coincidencia (un usuario con las credenciales proporcionadas), almacena el ID del rol en la sesión y redirige al usuario al menú de administrador.
     - Si las credenciales no coinciden, muestra un mensaje de error.
     - Cierra el statement al final del proceso. -->
<?php
// Incluyo el archivo de conexión a la base de datos.
include 'conexion.php';
// Inicio o reanudo la sesión.
session_start();

// Verifico si se envió el formulario de login.
if(isset($_POST['login'])){
    // Recojo el nombre de usuario y la contraseña del formulario.
    $usuario = $_POST['Usuario'];
    $password = $_POST['Password'];

    // Preparo la consulta SQL para verificar el usuario y la contraseña.
    $stmt = $con->prepare("SELECT idrol FROM rol WHERE nombre=? AND contraseña=?");
    $stmt->bind_param('ss', $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifico si las credenciales son correctas.
    if($result->num_rows == 1){
        // Obtengo el ID del rol y lo guardo en la sesión.
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['idrol']; 
        // Redirijo al usuario al menú de administrador.
        header("Location: menu_admin.php");
        exit;
    } else {
        // Muestro un mensaje de error si las credenciales son incorrectas.
        echo "Usuario o contraseña incorrectos";
    }

    // Cierro el statement.
    $stmt->close();
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
    <!-- Configuro el encabezado de la página con charset, viewport y título. -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Incluyo los estilos CSS para la página. -->
    <link rel="stylesheet" href="assets/css/estilos_login.css">
</head>

<body>
    <!-- Creo el formulario de login. -->
    <div class="login-container">
        <h1>Venfarma</h1>
        <div class="icon-container">
            <img src="/imagenes/baseline_account_circle_black_48dp.png" alt="Ícono de usuario">
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
