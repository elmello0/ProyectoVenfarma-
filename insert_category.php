
<?php

/* <!-- Este script PHP se encarga de insertar nuevas categorías en una base de datos.
     - Establece los detalles de conexión a la base de datos (servidor, nombre de usuario, contraseña, nombre de la base de datos).
     - Intenta conectarse a la base de datos usando PDO y establece el modo de error para manejar excepciones.
     - Prepara una sentencia SQL para insertar una nueva categoría con los datos recibidos por POST (nombre y descripción de la categoría).
     - Ejecuta la sentencia y, en caso de éxito, muestra un mensaje indicando que la categoría se añadió correctamente.
     - Captura y muestra cualquier error de PDO en caso de fallo en la conexión o en la consulta.
     - Cierra la conexión a la base de datos al final del script. -->*/

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparamos la sentencia SQL
    $stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)");
    $stmt->execute([$_POST['nombre_categoria'], $_POST['descripcion_categoria']]);

    echo "Categoría añadida con éxito!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

?>