<!-- Este script PHP gestiona la adición de clientes a una base de datos.
     - Establece los parámetros de conexión a la base de datos (servidor, usuario, contraseña, nombre de la base).
     - Inicializa la conexión con 'mysqli' y verifica si hay errores, deteniendo la ejecución si los hay.
     - Recoge los datos del cliente (nombre, RUT, correo, dirección) enviados a través de un formulario por POST.
     - Construye una consulta SQL para insertar los datos del cliente en la tabla 'clientes'.
     - Ejecuta la consulta y muestra un mensaje de éxito si se crea el registro correctamente, o un mensaje de error con detalles si falla.
     - Cierra la conexión a la base de datos al finalizar. -->
<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

// Recoger los datos del formulario
$nombreCliente = $_POST['nombreCliente'];
$rutCliente = $_POST['rutCliente'];
$correoCliente = $_POST['correoCliente'];
$direccionCliente = $_POST['direccionCliente'];

// La consulta SQL para insertar los datos
$sql = "INSERT INTO clientes (nombre, rut, correo, direccion) VALUES ('$nombreCliente', '$rutCliente', '$correoCliente', '$direccionCliente')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Nuevo registro creado con éxito";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
