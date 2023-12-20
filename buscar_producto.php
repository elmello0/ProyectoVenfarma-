
<?php
/*<!-- El código que proporcionó es un script PHP que se conecta a una base de datos, recupera datos según
un término de búsqueda y devuelve los resultados en formato JSON. -->*/

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Obtener el término de búsqueda desde la URL
$nombre_producto = isset($_GET['nombre_producto']) ? $_GET['nombre_producto'] : '';

// Preparar la consulta SQL para evitar inyecciones SQL
$stmt = $conn->prepare("SELECT * FROM producto WHERE nombre LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $nombre_producto);
$stmt->execute();
$result = $stmt->get_result();

// Preparar el array para los resultados
$productos = [];

// Obtener los resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode($productos);
} else {
    echo json_encode(["error" => "No se encontraron productos con ese nombre."]);
}

// Cerrar la conexión
$conn->close();
?>

