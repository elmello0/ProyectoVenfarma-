<!-- Este script PHP gestiona la conexión a una base de datos y recupera datos específicos.
     - Define las credenciales para la conexión a la base de datos (servidor, usuario, contraseña, nombre de la base de datos).
     - Recoge el nombre de la demanda de la solicitud GET, usando un valor vacío si no se proporciona.
     - Usa PDO para intentar una conexión segura con la base de datos:
       - Configura el modo de error de PDO para manejar excepciones.
       - Prepara una consulta SQL para obtener la descripción de una demanda específica, usando parámetros vinculados para mayor seguridad.
     - Ejecuta la consulta y maneja los resultados:
       - Si encuentra un registro, obtiene y envía la descripción.
       - Si no hay registros, informa que no se encontró la descripción.
     - En caso de error en la conexión o consulta, captura la excepción y muestra un mensaje de error.
     - Cierra la conexión a la base de datos al final del script, independientemente del resultado. -->
<?php
// Establezco las credenciales para conectarme a la base de datos.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Recojo el nombre de la demanda de la solicitud GET o uso un valor vacío si no se proporciona.
$nombreDemanda = isset($_GET['nombre']) ? $_GET['nombre'] : '';

try {
    // Intento establecer una conexión con la base de datos usando PDO.
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configuro el modo de error para manejar excepciones.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparo la consulta SQL para obtener la descripción de la demanda.
    $stmt = $conn->prepare("SELECT descripcion FROM demanda WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $nombreDemanda, PDO::PARAM_STR);

    // Ejecuto la consulta.
    $stmt->execute();

    // Verifico si encontré la demanda.
    if ($stmt->rowCount() > 0) {
        // Obtengo la descripción y la envío.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $descripcion = $row['descripcion'];
        echo $descripcion;
    } else {
        // Informo si no se encontró la descripción para el nombre proporcionado.
        echo "Descripción no encontrada para la demanda: " . htmlspecialchars($nombreDemanda);
    }

} catch (PDOException $e) {
    // Manejo la excepción en caso de un error y muestro el mensaje de error.
    echo "Error: " . $e->getMessage();
} finally {
    // Cierro la conexión a la base de datos.
    $conn = null;
}
?>
