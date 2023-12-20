
<?php
/*<!-- El código proporcionado es un script PHP que maneja una solicitud POST para eliminar una demanda de
una base de datos. -->*/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Verifico si se ha enviado el nombre de la demanda.
if(isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    try {
        // Conexión a la base de datos usando PDO.
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Comprobar la cantidad actual de la demanda.
        $stmt = $conn->prepare("SELECT cantidad FROM demanda WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la cantidad es 0.100, procedo a eliminar la demanda.
        if($result && $result['cantidad'] == 0.100) {
            $deleteStmt = $conn->prepare("DELETE FROM demanda WHERE nombre = :nombre");
            $deleteStmt->bindParam(':nombre', $nombre);
            $deleteStmt->execute();
            echo json_encode(['success' => true, 'message' => 'Demanda eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'La cantidad no es suficientemente baja para eliminar.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó el nombre de la demanda.']);
}
?>
