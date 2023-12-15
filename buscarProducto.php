<!-- El código que proporcionó es un script PHP que se conecta a una base de datos MySQL y recupera datos
según un término de búsqueda. Aquí hay un desglose de lo que hace el código: -->
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $terminoBusqueda = $_GET['terminoBusqueda'] ?? '';

    // Modificamos la consulta para incluir el campo 'estado'
    $sql = "SELECT p.idProducto, p.nombre, p.stock, p.precio, p.estado, 
           prom.nombre AS nombre_promocion, prom.descuento, 
           cat.nombre AS nombre_categoria, 
           fam.nombre AS nombre_familia, 
           tipo.nombre AS nombre_tipo
    FROM producto p
    LEFT JOIN promocion prom ON p.promocion_idpromocion = prom.idpromocion
    LEFT JOIN categoria cat ON p.categoria_idcategoria = cat.idcategoria
    LEFT JOIN familia fam ON p.familia_idfamilia = fam.idfamilia
    LEFT JOIN tipo tipo ON p.tipo_idtipo = tipo.idtipo
    WHERE p.nombre LIKE ?";
    $stmt = $conn->prepare($sql);
    $terminoLike = '%' . $terminoBusqueda . '%';
    $stmt->bindParam(1, $terminoLike, PDO::PARAM_STR);

    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>