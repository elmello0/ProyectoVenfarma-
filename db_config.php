<?php
// db_config.php
$servername = "localhost";
$dbname = "mydb";
$dbUsername = "root";
$dbPassword = "";

function connectToDatabase() {
    global $servername, $dbname, $dbUsername, $dbPassword;

    // Crear conexión
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}
?>
