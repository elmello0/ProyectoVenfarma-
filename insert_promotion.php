<!-- Este script PHP se utiliza para insertar una nueva promoción en la base de datos.
     - Configura la conexión a la base de datos con PDO y activa el manejo de excepciones.
     - Prepara una consulta SQL para insertar una promoción en la tabla 'promocion', utilizando datos proporcionados a través del método POST.
     - Ejecuta la consulta y, si es exitosa, devuelve una respuesta en formato JSON indicando el éxito.
     - En caso de error en la operación, captura la excepción de PDO y devuelve un mensaje de error en formato JSON.
     - Cierra la conexión a la base de datos al finalizar. -->
<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparamos la sentencia SQL
    $stmt = $conn->prepare("INSERT INTO promocion (nombre, estado, descuento, fecha_inicio, fecha_final) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nombre_promocion'],
        $_POST['estado_promocion'],
        $_POST['descuento_promocion'],
        $_POST['fecha_inicio_promocion'],
        $_POST['fecha_final_promocion'],
    ]);

    echo json_encode(['success' => 'Promoción añadida con éxito.']);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Error al añadir promoción: ' . $e->getMessage()]);
}

$conn = null;

?>