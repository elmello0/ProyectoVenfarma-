<!-- Este código PHP realiza una consulta a la base de datos para buscar empleados según un término de
búsqueda. -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$terminoBusqueda = $_GET['terminoBusqueda'] ?? '';

$sql = "SELECT e.idempleado, e.rut, e.nombre, e.apellido, 
               COALESCE(r.nombre, '') AS nombre_rol, 
               COALESCE(c.nombre, '') AS nombre_cargo 
        FROM empleado e 
        LEFT JOIN rol r ON e.rol_idrol = r.idrol 
        LEFT JOIN cargo c ON e.cargo_idcargo = c.idcargo
        WHERE e.nombre LIKE ? OR e.apellido LIKE ? OR e.rut LIKE ?";
        
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$terminoLike = '%' . $terminoBusqueda . '%';
$stmt->bind_param("sss", $terminoLike, $terminoLike, $terminoLike);

if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$result = $stmt->get_result();

$empleados = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $empleados[] = $row;
    }
} else {
    echo "No se encontraron empleados con ese término de búsqueda.";
    exit;
}

$conn->close();

// Devolver los datos en formato JSON
echo json_encode($empleados);
?>
