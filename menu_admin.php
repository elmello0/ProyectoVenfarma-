<!-- Este fragmento de código PHP se encarga de gestionar las sesiones de usuario.
     - Inicia o reanuda una sesión usando session_start(). Esto es necesario para tener acceso a las variables de sesión.
     - Verifica si el usuario actual está logueado. Esto se hace comprobando si existe un 'id' en la variable $_SESSION.
     - Si no hay un 'id' en la sesión (lo que significa que el usuario no está logueado), realiza una redirección.
     - La redirección se hace hacia 'login.php' usando la función header().
     - El uso de exit() después de la redirección asegura que el script PHP se detenga si se cumple la condición de no estar logueado.
     - Este código es típicamente utilizado al principio de un script para proteger páginas que requieren autenticación. -->
<?php
// Inicio o reanudo la sesión.
session_start();
// Verifico si el usuario está logueado. Si no, lo redirijo a la página de login.
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}
?>
<!-- Este documento HTML define la estructura de la página del menú de administración.
     - Comienza con la declaración DOCTYPE para HTML y define el idioma de la página como español.
     - En el <head>:
       - Establece el juego de caracteres como UTF-8.
       - Configura el viewport para asegurar la responsividad en dispositivos móviles.
       - Asigna un título a la página, en este caso, 'Menú Admin'.
       - Incluye una referencia a jQuery a través de un enlace CDN para funcionalidades JavaScript.
       - Vincula una hoja de estilos CSS externa para la apariencia de la página.
     - En el <body>:
       - Crea un encabezado (<header>) con un título (h3) para el menú.
       - Proporciona botones de navegación en forma de enlaces (<a>) que redirigen a distintas secciones de la administración: Menú Admin, Productos, Demandas, Historial y Empleados. Cada botón está encapsulado en un enlace (<a>) y se implementa como un elemento <button>.
     - El archivo no incluye scripts internos ni etiquetas adicionales en el cuerpo, centrándose en la navegación. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuro el encabezado de la página con charset, viewport y título. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <!-- Incluyo jQuery y los estilos CSS para la página. -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
</head>

<body>
    <!-- Creo el header de la página con botones de navegación. -->
    <header>
    <h3>Menú</h3>
        <a href="menu_admin.php"><button>Menú Admin</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
        <a href="getEmpleados.php"><button>Empleados</button></a>
    </header>

</body>
</html>
