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

    // Verificar si se recibió el nombre de la demanda
    if (isset($_POST['nombre'])) {
        $nombreDemanda = $_POST['nombre'];

        // Consulta SQL para obtener la descripción de la demanda seleccionada
        $query = "SELECT descripcion FROM demanda WHERE nombre = :nombre";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombreDemanda);
        $stmt->execute();

        // Obtener la descripción y enviarla como respuesta
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $response = [
                'success' => true,
                'descripcion' => $result['descripcion']
            ];
        } else {
            $response = [
                'success' => false,
                'error' => 'La demanda no tiene descripción.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'error' => 'No se proporcionó el nombre de la demanda.'
        ];
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);  // Aquí es donde se envía la respuesta

} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    $response = [
        'success' => false,
        'error' => 'Error de conexión a la base de datos: ' . $e->getMessage()
    ];
    echo json_encode($response);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>
