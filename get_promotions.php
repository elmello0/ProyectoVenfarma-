<!-- Este es un script PHP que se conecta a una base de datos MySQL y recupera datos de una tabla llamada
"promoción". Luego codifica los datos recuperados en formato JSON y los genera. -->
<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

header('Content-Type: application/json');

try {
    // Establecer la conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener las promociones
    $stmt = $conn->prepare("SELECT idpromocion, nombre, estado, descuento, fecha_inicio, fecha_final FROM promocion");


    $stmt->execute();

    // Construir un array con las promociones
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver las promociones en formato JSON
    echo json_encode($promotions);

} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn = null;
    }
}
?>
