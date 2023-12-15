<!-- Este script PHP actualiza datos de promociones en una base de datos.
     - Configura la conexión a la base de datos.
     - Recibe y valida datos del formulario (id, nombre, estado, descuento, fechas de inicio y final).
     - Si falta algún dato, devuelve un mensaje de error y termina el script.
     - Establece una conexión PDO con la base de datos.
     - Prepara y ejecuta una consulta SQL para actualizar la promoción.
     - Vincula los datos del formulario a la consulta.
     - Comprueba si se actualizó alguna fila y devuelve un mensaje de éxito o de no cambios.
     - Maneja posibles excepciones PDO, enviando un mensaje de error en caso de falla.
     - Cierra la conexión a la base de datos al finalizar. -->
<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Recibir los datos del formulario
$idPromocion = $_POST['idpromocion'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$estado = $_POST['estado'] ?? '';
$descuento = $_POST['descuento'] ?? 0;
$fechaInicio = $_POST['fecha_inicio'] ?? null;
$fechaFinal = $_POST['fecha_final'] ?? null;

// Validar los datos recibidos
if (!$idPromocion || !$nombre || !$estado || !$descuento || !$fechaInicio || !$fechaFinal) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
    exit;
}

try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta SQL
    $stmt = $conn->prepare("UPDATE promocion SET nombre = :nombre, estado = :estado, descuento = :descuento, fecha_inicio = :fecha_inicio, fecha_final = :fecha_final WHERE idpromocion = :idpromocion");

    // Vincular parámetros
    $stmt->bindParam(':idpromocion', $idPromocion, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':descuento', $descuento);
    $stmt->bindParam(':fecha_inicio', $fechaInicio);
    $stmt->bindParam(':fecha_final', $fechaFinal);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si se actualizó alguna fila
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Promoción actualizada con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizó ningún cambio.']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la promoción: ' . $e->getMessage()]);
}

// Cerrar conexión
$conn = null;
?>
