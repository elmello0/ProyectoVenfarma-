
<?php

/* <!-- Este script PHP inserta un nuevo producto en una base de datos.
     - Establece la conexión a la base de datos con PDO y configura el manejo de errores a excepción.
     - Recoge los datos del producto del formulario (nombre, stock, precio, estado, etc.) con manejo de valores por defecto.
     - Prepara una consulta SQL para insertar el producto en la tabla 'producto', vinculando los parámetros correspondientes.
     - Ejecuta la consulta y, si es exitosa, devuelve una respuesta JSON indicando el éxito de la operación.
     - En caso de error, captura la excepción y devuelve un mensaje de error en formato JSON.
     - Cierra la conexión a la base de datos al finalizar el proceso. -->*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Conectar a la base de datos
$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

// Configurar el manejo de errores de PDO a excepción
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recoger los datos del formulario
$nombre = $_POST['nombre'] ?? ''; 
$stock = $_POST['stock'] ?? 0;
$precio = $_POST['precio'] ?? 0.0;
$estado = $_POST['estado'] ?? ''; 
$promocion_idpromocion = $_POST['promocion_idpromocion'] ?? NULL;
$categoria_idcategoria = $_POST['categoria_idcategoria'] ?? NULL;
$familia_idfamilia = $_POST['familia_idfamilia'] ?? NULL;
$tipo_idtipo = $_POST['tipo_idtipo'] ?? NULL;

try {
    // Preparar la consulta SQL para insertar el producto
    $stmt = $conn->prepare("INSERT INTO producto (nombre, stock, precio, estado, promocion_idpromocion, categoria_idcategoria, familia_idfamilia, tipo_idtipo) VALUES (:nombre, :stock, :precio, :estado, :promocion_idpromocion, :categoria_idcategoria, :familia_idfamilia, :tipo_idtipo)");
    
    // Vincular los parámetros y ejecutar la consulta
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':promocion_idpromocion', $promocion_idpromocion, PDO::PARAM_INT);
    $stmt->bindParam(':categoria_idcategoria', $categoria_idcategoria, PDO::PARAM_INT);
    $stmt->bindParam(':familia_idfamilia', $familia_idfamilia, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_idtipo', $tipo_idtipo, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();
    
    // Devolver una respuesta de éxito
    echo json_encode(['success' => 'Producto añadido con éxito.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al añadir producto: ' . $e->getMessage()]);
}

// Cerrar la conexión
$conn = null;
?>
