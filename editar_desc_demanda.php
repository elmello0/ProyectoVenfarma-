
<?php

/*<!-- El código que proporcionaste es un script PHP que actualiza la descripción de una demanda en una
base de datos MySQL. Aquí hay un desglose de lo que hace el código:-->*/

// editar_desc_demanda.php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Recuperar los datos enviados desde el formulario
$nombreDemanda = $_POST['nombre'];
$nuevaDescripcion = $_POST['nuevaDescripcion'];

// Establecer la conexión
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la sentencia SQL para actualizar la descripción
    $stmt = $conn->prepare("UPDATE demanda SET descripcion = :descripcion WHERE nombre = :nombre");

    // Vincular parámetros
    $stmt->bindParam(':descripcion', $nuevaDescripcion);
    $stmt->bindParam(':nombre', $nombreDemanda);

    // Ejecutar la sentencia
    $stmt->execute();

    // Verificar si se actualizó alguna fila
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Descripción actualizada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la demanda o la descripción era la misma.']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>
