
<?php
/*<!-- Este script PHP maneja la actualización de datos en una base de datos.
     - Incluye 'conexion.php' para la conexión a la base de datos.
     - Verifica si la solicitud es POST.
     - Recolecta y sanea el nombre de la demanda enviado por POST.
     - Obtiene la cantidad actual de la demanda mediante una consulta SQL.
     - Si la cantidad actual es mayor a 0.100:
       - Disminuye la cantidad en la base de datos con otra consulta SQL.
       - Envía una respuesta JSON indicando éxito o error en la actualización.
     - Si la cantidad es 0.100 o menor:
       - Envía una respuesta JSON sugiriendo la posible eliminación de la demanda.
     - Si la solicitud no es POST, envía un error.
     - Cierra la conexión a la base de datos al final. -->*/
// Incluyo el archivo de conexión a la base de datos.
include 'conexion.php';

// Verifico si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recojo y limpio el nombre de la demanda enviado por POST.
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($con, $_POST['nombre']) : '';

    // Primero, obtengo la cantidad actual de la demanda.
    $queryCantidad = "SELECT cantidad FROM demanda WHERE nombre = '$nombre'";
    $cantidadActual = 0;
    if ($resultado = mysqli_query($con, $queryCantidad)) {
        $fila = mysqli_fetch_assoc($resultado);
        $cantidadActual = $fila['cantidad'];
    }

    // Si la cantidad es mayor que 0.100, la disminuyo. De lo contrario, envío una respuesta específica.
    if ($cantidadActual > 0.100) {
        $query = "UPDATE demanda SET cantidad = GREATEST(0.100, cantidad - 1) WHERE nombre = '$nombre'";
        if ($resultado = mysqli_query($con, $query)) {
            echo json_encode(['success' => 'Cantidad de demanda disminuida']);
        } else {
            echo json_encode(['error' => mysqli_error($con)]);
        }
    } else {
        // La cantidad ha llegado a 0.100, lo que indica que la demanda puede ser eliminada.
        echo json_encode(['deletePrompt' => true, 'message' => 'Cantidad de demanda es mínima. ¿Desea eliminar la demanda completamente?']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no válido']);
}

// Cierro la conexión a la base de datos.
mysqli_close($con);
?>