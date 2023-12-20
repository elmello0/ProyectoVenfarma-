
<?php

/*<!-- Este script PHP es para insertar demandas en una base de datos.
     - Configura las credenciales de conexión a la base de datos y establece una conexión utilizando PDO.
     - Activa el manejo de excepciones para errores de PDO.
     - Prepara una consulta SQL para insertar una nueva demanda en la tabla 'demanda' con datos proporcionados por POST.
     - Ejecuta la consulta y, en caso de éxito, muestra un mensaje confirmando la adición de la demanda.
     - Captura y muestra cualquier error de PDO durante la conexión o ejecución de la consulta.
     - Cierra la conexión a la base de datos al final del proceso. -->*/ 
     
// Configuro las credenciales para conectarme a la base de datos.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Intento establecer una conexión con la base de datos usando PDO.
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configuro el modo de error para manejar excepciones.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparo la consulta SQL para insertar una nueva demanda.
    $stmt = $conn->prepare("INSERT INTO demanda (nombre, fecha, descripcion, estado) VALUES (?, ?, ?, ?)");
    // Ejecuto la consulta utilizando los datos enviados por POST.
    $stmt->execute([
        $_POST['nombre_demanda'],
        $_POST['fecha_demanda'],
        $_POST['descripcion_demanda'],
        $_POST['estado_demanda']
    ]);

    // Informo del éxito de la operación.
    echo "Demanda añadida con éxito!";
} catch(PDOException $e) {
    // Manejo la excepción en caso de un error y muestro el mensaje de error.
    echo "Error: " . $e->getMessage();
}

// Cierro la conexión a la base de datos.
$conn = null;
?>
