
<?php

/*<!-- Este fragmento de código PHP se utiliza para manejar sesiones de usuario y control de acceso.
     - Comienza con session_start(), que inicia o reanuda una sesión existente. Esta función es crucial para trabajar con cualquier dato almacenado en las variables de sesión.
     - A continuación, verifica si el usuario actual está autenticado. Esto se realiza mediante la comprobación de la existencia de 'id' en la variable de sesión $_SESSION.
     - Si 'id' no está presente en la sesión (indicando que el usuario no está logueado), el script redirige al usuario a 'login.php'. Esto se logra mediante la función header(), que envía una cabecera HTTP de redirección.
     - Finalmente, el script llama a exit() para terminar su ejecución inmediatamente después de la redirección. Esto previene la ejecución de cualquier código adicional en caso de que el usuario no esté autenticado.
     - Este patrón es común en aplicaciones web para asegurar que ciertas páginas o recursos solo sean accesibles por usuarios autenticados. -->*/

// Inicio o reanudo la sesión.
session_start();
// Verifico si el usuario está logueado. Si no, lo redirijo a la página de login.
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}
?>
<!-- Este segmento del documento HTML define la sección del cuerpo (body) de la página, enfocado en la navegación y la visualización de productos.
     - Comienza con un título principal (h1) para la página, titulado 'Menú'.
     - Sigue con un encabezado (<header>) que contiene botones para diferentes secciones como Inicio, Productos, Demandas, y Historial. Estos botones facilitan la navegación por la página.
     - Importante: el botón 'Productos' tiene un atributo id 'btnProductos', pero incorrectamente incluye un atributo 'href' que no debería estar en un botón.
     - Debajo del encabezado, hay un contenedor div con id 'productosContainer'. Inicialmente está oculto (display: none) y sirve para mostrar el contenido de los productos.
     - Al final del body, se incluye un script de jQuery. Este script maneja el evento de clic en el botón 'Productos' (identificado por 'btnProductos').
     - El script realiza una solicitud AJAX a 'getProductos.php' usando el método GET.
     - En caso de éxito, la respuesta se muestra en el contenedor 'productosContainer', que se hace visible.
     - Si hay un error durante la solicitud AJAX, se muestra una alerta indicando un problema al cargar los productos.
     - Este enfoque dinámico permite cargar y mostrar los productos sin necesidad de recargar toda la página. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuro el encabezado de la página con charset, viewport y título. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <!-- Incluyo jQuery y los estilos CSS para la página. -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_empleado.css">
</head>

<body>
    <!-- Creo el título y el header de la página con botones de navegación. -->
    <h1>Menú</h1>
    <header>
        <button>Inicio</button>
        <button href="#" id="btnProductos">Productos</button>
        <button>Demandas</button>
        <button>Historial</button>
    </header>

    <!-- Div para mostrar el contenido de los productos. -->
    <div id="productosContainer" style="display: none;"></div>

    <!-- Script de jQuery para manejar el clic en el botón de productos. -->
    <script>
        $('#btnProductos').click(function() {
            // Realizo una solicitud AJAX para obtener los productos.
            $.ajax({
                url: 'getProductos.php',
                method: 'GET',
                success: function(data) {
                    // Muestro los productos en el contenedor.
                    $('#productosContainer').html(data).show();
                },
                error: function(err) {
                    // Alerta si hay un error al cargar los productos.
                    alert('Error al cargar productos');
                }
            });
        });
    </script>
</body>
</html>
