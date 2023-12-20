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

    // Determinar el campo por el cual buscar
    switch ($criterioBusqueda) {
        // Asegúrate de que los campos aquí coincidan con los nombres de las columnas en tu base de datos
        case 'stock':
            $campoBusqueda = 'p.stock';
            break;
        case 'promocion':
            $campoBusqueda = 'prom.nombre';
            break;
        case 'categoria':
            $campoBusqueda = 'cat.nombre';
            break;
        case 'familia':
            $campoBusqueda = 'fam.nombre';
            break;
        case 'tipo':
            $campoBusqueda = 'tipo.nombre';
            break;
        default:
            $campoBusqueda = 'p.nombre';
    }

    $sql = "SELECT p.idProducto, p.nombre, p.stock, p.precio, p.estado, 
           prom.estado AS estado_promocion, prom.nombre AS nombre_promocion, prom.descuento, 
           cat.nombre AS nombre_categoria, 
           fam.nombre AS nombre_familia, 
           tipo.nombre AS nombre_tipo
    FROM producto p
    LEFT JOIN promocion prom ON p.promocion_idpromocion = prom.idpromocion
    LEFT JOIN categoria cat ON p.categoria_idcategoria = cat.idcategoria
    LEFT JOIN familia fam ON p.familia_idfamilia = fam.idfamilia
    LEFT JOIN tipo tipo ON p.tipo_idtipo = tipo.idtipo
    WHERE $campoBusqueda LIKE ?";

    $stmt = $conn->prepare($sql);
    $terminoLike = '%' . $terminoBusqueda . '%';
    $stmt->bindParam(1, $terminoLike, PDO::PARAM_STR);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Registro de depuración
    error_log(print_r($productos, true)); // Esto agregará los productos al archivo de registro de PHP

    echo json_encode($productos);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
