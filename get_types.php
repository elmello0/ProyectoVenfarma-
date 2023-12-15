<!--El código que proporcionaste es un script PHP que se conecta a una base de datos MySQL y recupera
datos de una tabla llamada "tipo". Aquí hay un desglose de lo que hace el código: -->
<?php

// conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Establecer la conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener los tipos
    $query = "SELECT idtipo, nombre FROM tipo";
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Construir un array con los tipos
        $types = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $types[] = [
                'id' => $row['idtipo'], // Cambiado a 'id' para coincidir con el código JavaScript
                'nombre' => $row['nombre']
            ];
        }

        // Devolver los tipos en formato JSON
        echo json_encode($types);
    } else {
        // Manejar errores de la consulta
        echo json_encode(['error' => 'Error al obtener los tipos']);
    }
} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>
