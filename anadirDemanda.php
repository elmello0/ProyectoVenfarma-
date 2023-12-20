
<?php

/*<!-- El código proporcionado es un script PHP que maneja una solicitud POST para actualizar la cantidad
de una demanda en una base de datos. Aquí hay un desglose de lo que hace el código: -->*/

// Incluyo el archivo para conectarme a la base de datos.
include 'conexion.php'; 

// Verifico si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Chequeo si recibí un 'nombre' y si no está vacío.
    if (isset($_POST['nombre']) && trim($_POST['nombre']) !== '') {
        // Limpio el 'nombre' para seguridad.
        $nombre = mysqli_real_escape_string($con, $_POST['nombre']);

        // Preparo la consulta SQL para actualizar la cantidad.
        $query = "UPDATE demanda SET cantidad = cantidad + 1 WHERE nombre = '$nombre'";

        // Intento ejecutar la consulta.
        if ($resultado = mysqli_query($con, $query)) {
            // Chequeo si se actualizó algún registro.
            if (mysqli_affected_rows($con) > 0) {
                // Respondo con éxito si se actualizó la cantidad.
                echo json_encode(['success' => 'Cantidad de demanda incrementada en uno']);
            } else {
                // Respondo con error si no se encontró el registro.
                echo json_encode(['error' => 'No se encontró la demanda con ese nombre o ya está en la cantidad máxima']);
            }
        } else {
            // Respondo con el error de la consulta.
            echo json_encode(['error' => 'Error al ejecutar la consulta: ' . mysqli_error($con)]);
        }
    } else {
        // Respondo con error si el 'nombre' no es válido.
        echo json_encode(['error' => 'El nombre de la demanda no fue proporcionado o es inválido']);
    }
} else {
    // Respondo con error si el método no es POST.
    echo json_encode(['error' => 'Método de solicitud no válido']);
}

// Cierro la conexión a la base de datos.
mysqli_close($con);
?>
