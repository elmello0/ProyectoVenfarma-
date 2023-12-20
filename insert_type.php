
<?php

/* <!-- Este script PHP se encarga de añadir nuevos tipos a una base de datos.
     - Establece la conexión a la base de datos usando PDO, configurando el servidor, usuario, contraseña y nombre de la base.
     - Activa el manejo de excepciones para errores de PDO.
     - Prepara una sentencia SQL para insertar un nuevo tipo en la tabla 'tipo', utilizando el nombre proporcionado por POST.
     - Ejecuta la consulta y, en caso de éxito, muestra un mensaje confirmando la adición del tipo.
     - En caso de error, muestra el mensaje de error de PDO.
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
    $stmt = $conn->prepare("INSERT INTO tipo (nombre) VALUES (?)");
    $stmt->execute([$_POST['nombre_tipo']]);

    echo "Tipo añadido con éxito!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;

?>