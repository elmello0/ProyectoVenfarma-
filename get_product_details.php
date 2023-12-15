<!-- El código proporcionado es un script PHP que se conecta a una base de datos MySQL y recupera los
detalles de un producto según su ID. Aquí hay un desglose de lo que hace el código: -->
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

    // Verificar si se proporcionó un ID de producto
    if (isset($_GET['id'])) {
        $productId = $_GET['id'];

        // Consulta SQL para obtener los detalles del producto por ID
        $query = "SELECT * FROM producto WHERE idProducto = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$productId]);

        // Verificar si la consulta fue exitosa
        if ($stmt->rowCount() > 0) {
            // Obtener los detalles del producto
            $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            // Devolver los detalles en formato JSON
            echo json_encode(['success' => true, 'data' => $productDetails]);
        } else {
            // El producto no fue encontrado
            echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
        }
    } else {
        // No se proporcionó un ID de producto
        echo json_encode(['success' => false, 'error' => 'ID de producto no proporcionado']);
    }
} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
} finally {
    // Cerrar la conexión
    $conn = null;
}
