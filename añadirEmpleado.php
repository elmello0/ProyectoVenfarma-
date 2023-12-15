<!-- El código que proporcionó es un script PHP que se utiliza para agregar un empleado a una base de
datos. -->
<?php
// añadirEmpleado.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir los datos del formulario
$rut = $_POST['rut'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rol_id = $_POST['rol_idrol'];
$cargo_id = $_POST['cargo_idcargo'];

// Preparar y ejecutar la consulta
$sql = "INSERT INTO empleado (rut, nombre, apellido, rol_idrol, cargo_idcargo) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $rut, $nombre, $apellido, $rol_id, $cargo_id);

if ($stmt->execute()) {
    echo "Empleado añadido con éxito";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
