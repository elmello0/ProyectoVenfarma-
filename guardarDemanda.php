<!-- Este script PHP maneja la inserción de demandas en una base de datos.
     - Comprueba si la solicitud es POST, y si lo es:
       - Establece las credenciales de conexión a la base de datos.
       - Recoge los datos de la demanda enviados a través del formulario (nombre, fecha, descripción, estado, cantidad).
       - Intenta conectar con la base de datos usando PDO, prepara una consulta SQL para insertar la demanda y la ejecuta.
       - En caso de éxito, envía una respuesta JSON indicando que la demanda fue agregada.
       - Si hay un error de conexión, captura la excepción y envía un mensaje de error.
     - Si la solicitud no es POST, envía un mensaje de error indicando que el método de solicitud no es válido.
     - Cierra la conexión a la base de datos al final del proceso. -->
<?php
// Verifico si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Configuro las credenciales para conectarme a la base de datos.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";

    // Recojo los datos enviados a través del formulario.
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $cantidad = $_POST['cantidad'];

    try {
        // Intento establecer una conexión con la base de datos usando PDO.
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Configuro el modo de error para manejar excepciones.
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparo la consulta SQL para insertar la demanda.
        $stmt = $conn->prepare("INSERT INTO demanda (nombre, fecha, descripcion, estado, cantidad) VALUES (:nombre, :fecha, :descripcion, :estado, :cantidad)");

        // Vinculo los valores a la consulta.
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cantidad', $cantidad);

        // Ejecuto la consulta.
        $stmt->execute();

        // Envío una respuesta de éxito.
        echo json_encode(['success' => 'Demanda agregada correctamente']);
    } catch (PDOException $e) {
        // Manejo la excepción en caso de un error de conexión.
        echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    } finally {
        // Cierro la conexión a la base de datos.
        $conn = null;
    }
} else {
    // Envío un error si el método no es POST.
    echo json_encode(['error' => 'Método de solicitud no válido']);
}
?>
