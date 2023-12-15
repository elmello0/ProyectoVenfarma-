<!-- Este código PHP se encarga de la gestión de sesiones y control de acceso.
     - Empieza con session_start() para iniciar o continuar una sesión.
     - Verifica si el usuario está logueado comprobando si 'id' existe en $_SESSION.
     - Si no está logueado (no existe 'id'), redirige a 'login.php' y detiene el script con exit(). -->
<?php
// Inicio o reanudo la sesión.
session_start();
// Verifico si el usuario está logueado. Si no, lo redirijo a la página de login.
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuro el encabezado de la página con charset, viewport y título. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <!-- Incluyo jQuery y los estilos CSS para la página. -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_encargado.css">
</head>
<!-- Este código HTML estructura la parte visual y funcional de la página.
     - Incluye un título (h1) y un encabezado con botones para navegación (Inicio, Demandas, Historial).
     - Hay un div oculto ('productosContainer') para mostrar contenido dinámico.
     - Un script de jQuery maneja clics en 'btnProductos', realizando una solicitud AJAX a 'getProductos.php'.
     - Si la solicitud es exitosa, los productos se muestran en 'productosContainer'.
     - En caso de error, se muestra una alerta. -->
<body>
    <!-- Creo el título y el header de la página con botones de navegación. -->
    <h1>Menú</h1>
    <header>
        <button>Inicio</button>
        <button>Demandas</button>
        <button>Historial</button>
    </header>

    <!-- Div para mostrar contenido dinámico, inicialmente oculto. -->
    <div id="productosContainer" style="display: none;"></div>

    <!-- Script de jQuery para la interacción con el botón de productos. -->
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
