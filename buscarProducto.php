<?php
/* Este script PHP se conecta a una base de datos MySQL y recupera datos
   según un término de búsqueda proporcionado por el usuario. */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $terminoBusqueda = $_GET['terminoBusqueda'] ?? '';
    $criterioBusqueda = $_GET['criterio'] ?? 'nombre'; // Criterio de búsqueda predeterminado

    // Mapeo seguro de criterios de búsqueda a nombres de columnas de base de datos
    $allowedSearchFields = [
        'nombre' => 'p.nombre',
        'stock' => 'p.stock',
        'precio' => 'p.precio',
        'descuento' => 'prom.descuento',
        'promocion' => 'prom.nombre',
        'categoria' => 'cat.nombre',
        'familia' => 'fam.nombre',
        'tipo' => 'tipo.nombre',
    ];

    // Determinar el campo por el cual buscar de manera segura
    $campoBusqueda = $allowedSearchFields[$criterioBusqueda] ?? 'p.nombre';

    // Preparar la consulta SQL
    $sql = "SELECT p.idProducto, p.nombre, p.stock, p.precio, p.estado, 
           prom.estado AS estado_promocion, prom.nombre AS nombre_promocion, prom.descuento, 
           cat.nombre AS nombre_categoria, 
           fam.nombre AS nombre_familia, 
           tipo.nombre AS nombre_tipo
    FROM producto p
    LEFT JOIN promocion prom ON p.promocion_idpromocion = prom.idpromocion
    LEFT JOIN categoria cat ON p.categoria_idcategoria = cat.idcategoria
    LEFT JOIN familia fam ON p.familia_idfamilia = fam.idfamilia
    LEFT JOIN tipo tipo ON p.tipo_idtipo = tipo.idtipo";

      if (in_array($criterioBusqueda, ['precio', 'stock', 'descuento'])) {
        // Búsqueda que comienza con los dígitos proporcionados
        $sql .= " WHERE {$campoBusqueda} LIKE ?";
        $terminoBusqueda = $terminoBusqueda . '%';
    } else {
        // Búsqueda de texto con LIKE
        $sql .= " WHERE {$campoBusqueda} LIKE ?";
        $terminoLike = '%' . $terminoBusqueda . '%';
    }


    $stmt = $conn->prepare($sql);

    if (in_array($criterioBusqueda, ['precio', 'stock', 'descuento'])) {
        $stmt->bindParam(1, $terminoBusqueda);
    } else {
        $stmt->bindParam(1, $terminoLike, PDO::PARAM_STR);
    }

    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
