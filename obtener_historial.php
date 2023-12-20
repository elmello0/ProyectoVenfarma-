<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Asegúrate de que no haya ningún contenido antes de esta línea
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Comprueba la conexión
if ($conn->connect_error) {
    die(json_encode(['error' => "Conexión fallida: " . $conn->connect_error]));
}

// Consulta SQL para obtener el historial
$consulta = "SELECT h.fecha, h.descripcion, p.nombre as producto_nombre, e.nombre as empleado_nombre 
             FROM historial h
             LEFT JOIN historial_has_producto hhp ON h.idhistorial = hhp.historial_idhistorial
             LEFT JOIN producto p ON hhp.producto_idProducto = p.idProducto
             LEFT JOIN empleado e ON h.idempleado = e.idempleado
             ORDER BY h.fecha DESC";

$resultado = $conn->query($consulta);

$historial = [];

// Verificar si se obtuvieron resultados
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $historial[] = $fila;
    }
}

// Establece el tipo de contenido a JSON y codifica la salida
header('Content-Type: application/json');
echo json_encode($historial);

// Cerrar la conexión
$conn->close();
