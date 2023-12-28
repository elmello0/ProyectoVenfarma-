<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

header('Content-Type: application/json');

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Asegúrate de que tienes un parámetro `promotionId` en la URL
    $promotionId = isset($_GET['promotionId']) ? intval($_GET['promotionId']) : 0;

    // Preparar la consulta SQL para obtener los productos asociados con el ID de la promoción
    $stmt = $conn->prepare("SELECT * FROM producto WHERE promocion_idpromocion = :promotionId");
    $stmt->bindParam(':promotionId', $promotionId, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los productos en formato JSON
    echo json_encode(['success' => true, 'products' => $products]);

} catch (PDOException $e) {
    // Si hay un error, devolver un mensaje de error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Cerrar la conexión
$conn = null;

?>
