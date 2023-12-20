<?php


/*<!-- El código que proporcionaste es un script PHP que se conecta a una base de datos MySQL y recupera
datos de una tabla llamada "familia". Aquí hay un desglose de lo que hace el código: --> */

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Establecer la conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener las familias
    $query = "SELECT idfamilia, nombre FROM familia";
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Construir un array con las familias
        $families = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $families[] = [
                'id' => $row['idfamilia'], // Cambiado a 'id' para coincidir con el código JavaScript
                'nombre' => $row['nombre']
            ];
        }

        // Devolver las familias en formato JSON
        echo json_encode($families);
    } else {
        // Manejar errores de la consulta
        echo json_encode(['error' => 'Error al obtener las familias']);
    }
} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>
