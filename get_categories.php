<!-- El código que proporcionó es un script PHP que se conecta a una base de datos MySQL y recupera
categorías de una tabla llamada "categoría". Luego convierte los datos recuperados al formato JSON y
los repite como respuesta. -->
<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Establecer la conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener las categorías
    $query = "SELECT idcategoria, nombre FROM categoria";
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Construir un array con las categorías
        $categories = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = [
                'id' => $row['idcategoria'], // Cambiado a 'id' para coincidir con el código JavaScript
                'nombre' => $row['nombre']
            ];
        }

        // Devolver las categorías en formato JSON
        echo json_encode($categories);
    } else {
        // Manejar errores de la consulta
        echo json_encode(['error' => 'Error al obtener las categorías']);
    }
} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>
