
<?php
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($con, $_POST['nombre']) : '';

    // Obtener la cantidad actual de la demanda.
    $queryCantidad = "SELECT cantidad FROM demanda WHERE nombre = '$nombre'";
    $cantidadActual = 0;
    if ($resultado = mysqli_query($con, $queryCantidad)) {
        $fila = mysqli_fetch_assoc($resultado);
        $cantidadActual = $fila['cantidad'];
    }

    // Decremento la cantidad si es mayor a un cierto umbral.
    if ($cantidadActual > 0.100) {
        $nuevaCantidad = max(0.100, $cantidadActual - 1); // Evita que la cantidad sea menor que 0.100
        $query = "UPDATE demanda SET cantidad = '$nuevaCantidad' WHERE nombre = '$nombre'";
        if ($resultado = mysqli_query($con, $query)) {
            // Devuelve la cantidad actualizada y el nombre para actualizar el historial y el gráfico.
            echo json_encode([
                'success' => true,
                'cantidad' => $nuevaCantidad,
                'nombre' => $nombre
            ]);
        } else {
            echo json_encode(['error' => mysqli_error($con)]);
        }
    } else {
        // Sugerir eliminación completa de la demanda si la cantidad es igual o menor a 0.100
        echo json_encode([
            'deletePrompt' => true,
            'message' => 'Cantidad de demanda es mínima. ¿Desea eliminar la demanda completamente?',
            'nombre' => $nombre
        ]);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no válido']);
}

mysqli_close($con); // Cerrar la conexión a la base de datos.
?>
