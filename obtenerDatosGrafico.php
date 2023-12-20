
<?php


 /*<!-- Este script PHP se centra en obtener y procesar datos para un gráfico.
     - Comienza incluyendo 'conexion.php' para la conexión a la base de datos.
     - Prepara una consulta SQL que suma y agrupa cantidades de demanda por nombre.
     - Inicializa un arreglo, $datosParaGrafico, para almacenar los datos del gráfico.
     - Ejecuta la consulta y maneja los resultados:
       - Si es exitosa, procesa y almacena cada fila en $datosParaGrafico.
       - Convierte la 'cantidad' a float para precisión numérica.
       - Libera los recursos del resultado al finalizar.
     - En caso de error en la consulta, se prepara un mensaje de error para el gráfico.
     - Cierra la conexión con la base de datos.
     - Finalmente, envía los datos en formato JSON para su uso en la visualización del gráfico. */
     
// Incluyo el archivo de conexión a la base de datos.
include 'conexion.php';

// Preparo una consulta SQL para sumar las cantidades de demanda y agruparlas por nombre.
$query = "SELECT nombre, SUM(cantidad) AS cantidad FROM demanda GROUP BY nombre";

// Inicializo un arreglo para almacenar los datos del gráfico.
$datosParaGrafico = [['Nombre', 'Cantidad']]; 

// Ejecuto la consulta y verifico si fue exitosa.
if ($result = mysqli_query($con, $query)) {
    // Proceso cada fila del resultado.
    while ($row = mysqli_fetch_assoc($result)) {
        // Agrego los datos al arreglo para el gráfico.
        $datosParaGrafico[] = [$row['nombre'], floatval($row['cantidad'])];
    }
    // Libero el resultado de la memoria.
    mysqli_free_result($result);
} else {
    // Si hay un error, preparo un mensaje de error para el gráfico.
    $datosParaGrafico = [['Error', 'No se pudo recuperar los datos']];
}

// Cierro la conexión a la base de datos.
mysqli_close($con);

// Envío los datos del gráfico en formato JSON.
echo json_encode($datosParaGrafico);
?>
