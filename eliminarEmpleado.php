
<?php

/**<!-- El código es un script PHP que se conecta a una base de datos MySQL y elimina un registro de
empleado según la identificación del empleado proporcionada. -->*/ 

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


// Comprueba si se recibió el ID del empleado a través del método POST
if (isset($_POST['idempleado']) && !empty($_POST['idempleado'])) {
    $idEmpleado = $_POST['idempleado'];

    // Preparar la consulta SQL para eliminar el empleado
    $sql = "DELETE FROM empleado WHERE idempleado = ?";

    // Preparar la declaración preparada para evitar inyecciones SQL
    if ($stmt = $conn->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("i", $idEmpleado);  // "i" indica que la variable es de tipo entero

        // Ejecutar la declaración preparada
        if ($stmt->execute()) {
            echo "Empleado eliminado con éxito.";
        } else {
            echo "Error al eliminar el empleado: " . $stmt->error;
        }

        // Cerrar declaración
        $stmt->close();
    } else {
        echo "Error al preparar la declaración: " . $conn->error;
    }

    // Cerrar conexión
    $conn->close();
} else {
    echo "No se proporcionó el ID del empleado para eliminar.";
}
?>
