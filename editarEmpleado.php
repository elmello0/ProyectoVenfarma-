<!-- El código es un script PHP que procesa la edición de la información de un empleado. -->
<?php
// procesar_edicion_empleado.php
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

// Asegurarse de que se está recibiendo un POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $idempleado = $_POST['idempleado'];
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rol_idrol = $_POST['rol_idrol'];
    $cargo_idcargo = $_POST['cargo_idcargo'];

    // Preparar la consulta SQL para actualizar los datos
    $sql = "UPDATE empleado SET rut = ?, nombre = ?, apellido = ?, rol_idrol = ?, cargo_idcargo = ? WHERE idempleado = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiii", $rut, $nombre, $apellido, $rol_idrol, $cargo_idcargo, $idempleado);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Empleado actualizado con éxito";
    } else {
        echo "Error al actualizar el empleado: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
