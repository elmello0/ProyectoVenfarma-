<!-- El código que proporcionó es un script PHP que recupera datos de una base de datos MySQL y los
devuelve en formato JSON. Aquí hay un desglose de lo que hace el código: -->
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";
$productId = $_GET['id'] ?? ''; // Utiliza el operador de fusión null de PHP 7

// Validación de $productId
if (!is_numeric($productId) || $productId <= 0) {
    echo json_encode(['error' => "ID de producto inválido."]);
    exit;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta SQL para obtener los datos del producto
    $stmt = $conn->prepare("SELECT producto.*, promocion.nombre as nombre_promocion FROM producto LEFT JOIN promocion ON producto.promocion_idpromocion = promocion.idpromocion WHERE producto.idProducto = :idProducto");
$stmt->bindParam(':idProducto', $productId, PDO::PARAM_INT);
$stmt->execute();

    // Obtener los datos del producto
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['error' => "Producto no encontrado."]);
        exit;
    }

    // Establece un valor predeterminado para 'estado' si es nulo
    $product['estado'] = $product['estado'] ?? 'No especificado';

    // Devolver los datos en formato JSON
    echo json_encode($product);
} catch(PDOException $e) {
    echo json_encode(['error' => "Error al obtener los datos del producto: " . $e->getMessage()]);
}

$conn = null;

?>
