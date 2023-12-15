<!-- Este script PHP gestiona la actualización de datos de productos en una base de datos.
     - Configura el registro de errores de PHP para guardarlos en un archivo específico.
     - Establece los detalles de conexión a la base de datos.
     - Establece el tipo de contenido como JSON para la respuesta HTTP.
     - Inicia una conexión PDO con la base de datos y configura el manejo de errores.
     - Recoge datos del formulario enviado mediante POST.
     - Inicia una transacción en la base de datos para realizar la actualización.
     - Prepara y ejecuta una consulta SQL para actualizar un producto en la base de datos.
     - Vincula los datos del formulario a la consulta SQL.
     - Si la actualización es exitosa, confirma la transacción y consulta el producto actualizado para devolverlo en la respuesta.
     - Si no se actualiza ningún registro, revierte la transacción.
     - Captura y maneja cualquier excepción PDO, revirtiendo la transacción y devolviendo un mensaje de error.
     - Cierra la conexión a la base de datos al final del script. -->
<?php
// Configuración de reporte de errores
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\htdocs\VENFARMA\errores.log');

// Información de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

header('Content-Type: application/json');

try {
    // Conexión con la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recolectar datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $precio = $_POST['precio'] ?? 0.0;
    $estado = $_POST['estado'] ?? 'No especificado';
    $promocion_idpromocion = $_POST['promocion_idpromocion'] ?? NULL;
    $categoria_idcategoria = $_POST['categoria_idcategoria'] ?? NULL;
    $familia_idfamilia = $_POST['familia_idfamilia'] ?? NULL;
    $tipo_idtipo = $_POST['tipo_idtipo'] ?? NULL;
    $idProducto = $_POST['idProducto'] ?? 0;

    // Iniciar transacción
    $conn->beginTransaction();

    // Preparar consulta SQL para actualizar datos
    $stmt = $conn->prepare("UPDATE producto SET 
        nombre = :nombre, 
        stock = :stock, 
        precio = :precio, 
        estado = :estado, 
        promocion_idpromocion = :promocion_idpromocion, 
        categoria_idcategoria = :categoria_idcategoria, 
        familia_idfamilia = :familia_idfamilia, 
        tipo_idtipo = :tipo_idtipo 
        WHERE idProducto = :idProducto");

    // Vincular parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':promocion_idpromocion', $promocion_idpromocion, PDO::PARAM_INT);
    $stmt->bindParam(':categoria_idcategoria', $categoria_idcategoria, PDO::PARAM_INT);
    $stmt->bindParam(':familia_idfamilia', $familia_idfamilia, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_idtipo', $tipo_idtipo, PDO::PARAM_INT);
    $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);

    // Ejecutar consulta
    $stmt->execute();

    // Confirmar transacción si la actualización fue exitosa
    if ($stmt->rowCount() > 0) {
        $conn->commit();

        // Consultar producto actualizado
        $stmt = $conn->prepare("SELECT * FROM producto WHERE idProducto = :idProducto");
        $stmt->execute([':idProducto' => $idProducto]);
        $updatedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver resultado
        echo json_encode(['success' => true, 'product' => $updatedProduct]);
    } else {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado o no actualizado']);
    }
} catch(PDOException $e) {
    $conn->rollBack(); // Revertir transacción si hay error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Cerrar conexión
?>
