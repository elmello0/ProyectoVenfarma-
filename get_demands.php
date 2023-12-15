<!-- El código que proporcionó es un script PHP que se conecta a una base de datos MySQL usando PDO
(objetos de datos PHP) y recupera datos de demanda de una tabla llamada "demanda". -->
<?php
// Establezco las credenciales para conectarme a la base de datos.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Intento conectarme a la base de datos usando PDO.
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establezco el modo de error para manejar excepciones.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparo la consulta SQL para obtener demandas.
    $query = "SELECT iddemanda, nombre, estado FROM demanda";
    $result = $conn->query($query);

    // Verifico si obtuve resultados.
    if ($result) {
        $demands = [];
        // Recorro los resultados y los almaceno en un arreglo.
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $demands[] = $row;
        }

        // Envío las demandas en formato JSON.
        echo json_encode($demands);
    } else {
        // Envío un error si no pude obtener las demandas.
        echo json_encode(['error' => 'Error al obtener las demandas']);
    }
} catch (PDOException $e) {
    // Manejo la excepción en caso de un error de conexión.
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
} finally {
    // Cierro la conexión a la base de datos.
    $conn = null;
}
?>
