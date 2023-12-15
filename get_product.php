<!--El código que proporcionó es un script PHP que se conecta a una base de datos MySQL, recupera datos
de un producto específico según el ID pasado en la URL y devuelve los datos en formato JSON. -->
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";
$productId = $_GET['id']; // Obtener el ID del producto de la URL

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta SQL para obtener los datos del producto
    $stmt = $conn->prepare("SELECT * FROM producto WHERE idProducto = :idProducto");
    $stmt->execute(['idProducto' => $productId]);

    // Obtener los datos del producto
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode($product);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

?>