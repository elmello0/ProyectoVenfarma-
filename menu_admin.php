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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
</head>

<body>
    <?php
    session_start();

    // Verificar si el usuario está logueado y tiene un rol asignado
    if (!isset($_SESSION['role_name'])) {
        header('Location: login.php'); // Redireccionar al login si no está logueado
        exit();
    }

    $rolUsuario = $_SESSION['role_name']; // Obtener el rol del usuario
    ?>

    <header>
    
    <!-- Menú dinámico según el rol -->
    <?php if ($rolUsuario == 'admin'): ?>
      <h3>Venfarma
        administración</h3>
      </h3>
        <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
        <a href="getEmpleados.php"><button>Empleados</button></a>
    <?php elseif ($rolUsuario == 'empleado'): ?>
      <h3>Venfarma
        Empleado</h3>
      </h3>
        <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
    <?php elseif ($rolUsuario == 'encargado'): ?>
      <h3>Venfarma
        Reabastecimientos</h3>
      </h3>
      <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
    <?php else: ?>
        <p>Rol no reconocido.</p>
    <?php endif; ?>
    </header>

</body>
</html>
