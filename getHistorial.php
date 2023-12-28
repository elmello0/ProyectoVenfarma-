

<?php


/*<!-- Este código  inicia una sesión para verificar la autenticación del usuario. Si el usuario no está autenticado,
lo redirige a la página de inicio de sesión. Luego, incluye un archivo de conexión a la base de datos y configura la visualización de errores
para facilitar la depuración. Realiza una consulta a la base de datos para obtener el historial de registros
y muestra un mensaje de error si la consulta falla. -->*/



// archivo de conexión a la base de datos
include 'conexion.php';

// Habilito la visualización de errores (para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Consulto la base de datos para obtener el historial
$query = "SELECT historial.idhistorial, historial.fecha, historial_pdf.ruta_pdf, historial_pdf.id_pdf
          FROM historial
          LEFT JOIN historial_pdf ON historial.idhistorial = historial_pdf.id_historial
          ORDER BY historial.fecha DESC";
$resultado = mysqli_query($con, $query);

// Verifico si hay errores en la consulta
if (!$resultado) {
    echo "Error en la consulta: " . mysqli_error($con);
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">

<!-- Este código  realiza una consulta a una base de datos para obtener un historial de registros. 
Incluye funciones de inicio de sesión y redirección en caso de que el usuario no esté autenticado.
Además, se configura para mostrar errores en caso de problemas en la consulta SQL. -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn_generar_historial').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Solicitar confirmación del usuario
        if (confirm("¿Quieres generar un historial ahora mismo?")) {
            // Realiza una solicitud AJAX al script que genera el PDF
            $.ajax({
                url: 'generar_historial_pdf.php',
                type: 'POST',
                dataType: 'json', // Especifica que esperas una respuesta en formato JSON
                success: function(response) { // 'response' ya es un objeto JSON
    console.log(response); // Verifica la respuesta en la consola
    // Si se ha generado el historial con éxito
    if (response.idHistorialPdf) { // Cambia 'idGenerado' por 'idHistorialPdf'
        alert('Historial generado con éxito.');
        // Recargar la tabla con los nuevos datos
        recargarTablaHistorial();
    } else if (response.error) {
        alert('No se pudo generar el historial: ' + response.error);
    } else {
        alert('No se pudo generar el historial por una razón desconocida.');
    }
},

                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown); // Muestra más detalles del error
                    alert('Hubo un error al intentar generar el historial: ' + textStatus);
                }
            });
        }
    });
});
/**
 * Esta función realiza una solicitud AJAX para recargar la tabla de historial en una página web.
 * La tabla se llena con datos obtenidos del archivo 'recargar_tabla_historial.php'.
 */
function recargarTablaHistorial() {
    $.ajax({
        url: 'recargar_tabla_historial.php',
        type: 'GET',
        dataType: 'json', 
        success: function(response) { // 'response' ya es un objeto JSON
            var tableBody = document.getElementById('historial_table_body');
            tableBody.innerHTML = ''; // Limpiar la tabla actual
            response.forEach(function(historial) {
                var row = tableBody.insertRow();
                row.insertCell(0).innerHTML = historial.idhistorial;
                row.insertCell(1).innerHTML = new Date(historial.fecha).toLocaleString('es-ES', {
                    year: 'numeric', month: '2-digit', day: '2-digit',
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                });
                var descargaLink = historial.ruta_pdf ? '<a href="descargar_historial_pdf.php?id=' + historial.id_pdf + '">Descargar</a>' : 'No disponible';
                row.insertCell(2).innerHTML = descargaLink;
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown); 
            alert('Hubo un error al recargar la tabla de historial: ' + textStatus);
        }
    });
}

</script>

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
    Historial</h3>
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

    <!-- Botón para generar nuevo historial -->
    <button id="btn_generar_historial">Generar Historial</button>

    <!-- Tabla para mostrar el historial -->
    <table>
    <thead>
        <tr>
            <th>N° Historial</th>
            <th>Fecha de emisión</th>
            <th>Descargar</th>
        </tr>
    </thead>
 <!-- Este código que proporcionó es responsable de mostrar los registros de la base de datos en formato de
tabla en la página web.-->
    <tbody id="historial_table_body">
        <!-- Cargar registros del historial desde la base de datos -->
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['idhistorial']); ?></td>
                <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($fila['fecha']))); ?></td>
                <td>
                    <?php if (!empty($fila['ruta_pdf'])): ?>
                        <a href="descargar_historial_pdf.php?id=<?php echo $fila['id_pdf']; ?>">Descargar</a>
                    <?php else: ?>
                        No disponible
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>

</table>
</body>
</html>
<?php
mysqli_close($con);
?>
