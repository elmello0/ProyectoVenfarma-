<!-- Este script PHP se utiliza para añadir una nueva familia a una base de datos.
     - Establece la conexión a la base de datos utilizando PDO con las credenciales proporcionadas.
     - Configura PDO para que muestre excepciones en caso de errores.
     - Prepara una consulta SQL para insertar un nuevo registro en la tabla 'familia' con un nombre proporcionado a través del método POST.
     - Ejecuta la consulta y, si es exitosa, muestra un mensaje confirmando que la familia fue añadida.
     - En caso de error, captura la excepción de PDO y muestra el mensaje de error.
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
    $stmt = $conn->prepare("INSERT INTO familia (nombre) VALUES (?)");
    $stmt->execute([$_POST['nombre_familia']]);

    echo "Familia añadida con éxito!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

?>
