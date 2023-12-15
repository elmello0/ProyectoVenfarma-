<!-- El código proporcionado es un script PHP que maneja una solicitud POST para eliminar un registro de
una tabla de base de datos. Aquí hay un desglose de lo que hace el código: -->
<?php
// Incluyo el archivo de conexión a la base de datos.
include 'conexion.php';

// Verifico si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtengo el nombre de la demanda desde el formulario.
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';

    // Preparo la consulta SQL para eliminar una demanda.
    $query = "DELETE FROM demanda WHERE nombre = ?";

    // Intento preparar la consulta.
    if ($stmt = mysqli_prepare($con, $query)) {
        // Vinculo el parámetro 'nombre' a la consulta.
        mysqli_stmt_bind_param($stmt, "s", $nombre);

        // Ejecuto la consulta.
        mysqli_stmt_execute($stmt);

        // Verifico si se eliminó la demanda.
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Respondo con éxito si se eliminó la demanda.
            echo json_encode(['success' => 'Demanda eliminada']);
        } else {
            // Respondo con error si no se encontró la demanda.
            echo json_encode(['error' => 'No se encontró la demanda con ese nombre']);
        }

        // Cierro el statement.
        mysqli_stmt_close($stmt);
    } else {
        // Respondo con el error de la consulta.
        echo json_encode(['error' => mysqli_error($con)]);
    }
} else {
    // Respondo con error si el método no es POST.
    echo json_encode(['error' => 'Método de solicitud no válido']);
}

// Cierro la conexión a la base de datos.
mysqli_close($con);
?>
