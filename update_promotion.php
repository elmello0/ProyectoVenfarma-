<!-- Este script PHP actualiza una promoción en una base de datos.
     - Establece los detalles de conexión a la base de datos.
     - Recoge datos de una promoción desde un formulario.
     - Utiliza PDO para una conexión segura a la base de datos.
     - Prepara y ejecuta una consulta SQL para actualizar la promoción.
     - Los datos se pasan a la consulta utilizando marcadores de posición anónimos.
     - Devuelve un mensaje de éxito si la actualización es exitosa.
     - Captura y maneja excepciones, devolviendo un mensaje de error en caso de fallo.
     - Cierra la conexión a la base de datos al final. -->
<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Recoger los datos del formulario
$idpromocion = $_POST['idpromocion'];
$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$descuento = $_POST['descuento'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_final = $_POST['fecha_final'];


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE promocion SET nombre = ?, estado = ?, descuento = ?, fecha_inicio = ?, fecha_final = ? WHERE idpromocion = ?");
    $stmt->execute([$nombre, $estado, $descuento, $fecha_inicio, $fecha_final,$idpromocion]);

    echo json_encode(['success' => 'Promoción actualizada con éxito']);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Error al actualizar la promoción: ' . $e->getMessage()]);
}

$conn = null;
?>
