
<?php

/*<!-- Este script PHP está diseñado para recargar y mostrar una tabla de historial desde una base de datos.
     - Incluye 'conexion.php' para la conexión con la base de datos.
     - Ejecuta una consulta SQL que selecciona y junta datos de las tablas 'historial' y 'historial_pdf', ordenándolos por fecha descendente.
     - Maneja el resultado de la consulta:
       - Si hay un error en la consulta, envía un mensaje de error en formato JSON y detiene el script.
       - Si la consulta es exitosa, extrae los registros en un arreglo asociativo.
     - Devuelve los registros en formato JSON para su uso posterior, como en una interfaz de usuario.
     - Cierra la conexión con la base de datos al final del script. -->*/
// recargar_tabla_historial.php
include 'conexion.php'; // Asegúrate de que este path sea correcto y de que tu archivo conexion.php esté configurado correctamente.

$query = "SELECT historial.idhistorial, historial.fecha, historial_pdf.ruta_pdf, historial_pdf.id_pdf
          FROM historial
          LEFT JOIN historial_pdf ON historial.idhistorial = historial_pdf.id_historial
          ORDER BY historial.fecha DESC";

$resultado = mysqli_query($con, $query);

if (!$resultado) {
    // Si algo va mal al ejecutar la consulta, envía un mensaje de error.
    echo json_encode(array('error' => 'Error al recuperar los registros de la base de datos: ' . mysqli_error($con)));
    exit;
}

$registros = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Devolver los datos en formato JSON
echo json_encode($registros);

// No olvides cerrar la conexión a la base de datos
mysqli_close($con);
?>
