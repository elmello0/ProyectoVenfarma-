<?php
// Incluye la biblioteca TCPDF y el archivo de conexión.
require_once('C:/xampp/htdocs/VENFARMA/TCPDF-main/tcpdf.php');
include 'conexion.php'; // Tu archivo de conexión a la base de datos.

function obtenerNombreAdicional($conexion, $tabla, $columnaId, $id) {
    $query = "SELECT nombre FROM $tabla WHERE $columnaId = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        return $fila['nombre'];
    }
    return "No disponible";
}
/**
 * La función "obtenerInformacionAdicional" recupera información adicional de una tabla de base de
 * datos basada en un ID determinado y la devuelve como una matriz asociativa.
 * 
 * El parámetro `` es un objeto de conexión a la base de datos. Se utiliza
 * para establecer una conexión a una base de datos y ejecutar consultas.
 * El parámetro "tabla" hace referencia al nombre de la tabla de la base de datos de la
 * que se desea recuperar información adicional.
 * El parámetro "columnaId" es el nombre de la columna de la tabla que se utiliza para
 * identificar la fila específica. Normalmente es un número entero o un identificador único.
 * El parámetro "id" es el valor de la columna especificada por el parámetro "columnaId". Se
 * utiliza para filtrar la consulta y recuperar la fila específica de la tabla.
 * Una matriz de nombres de columnas que desea recuperar de la tabla.
 * 
 * una matriz que contiene los valores de las columnas especificadas de la tabla especificada
 * donde la columna con el ID especificado coincide con el valor de ID especificado. Si se encuentra
 * una fila, la función devuelve la fila como una matriz asociativa. Si no se encuentra ninguna fila,
 * la función devuelve una matriz con las columnas especificadas como claves y el valor "No disponible"
 * para cada clave.
 */

function obtenerInformacionAdicional($conexion, $tabla, $columnaId, $id, $columnas) {
    $select = implode(', ', $columnas);
    $query = "SELECT $select FROM $tabla WHERE $columnaId = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        return $fila;
    }
    return array_fill_keys($columnas, "No disponible");
}

/* Este bloque de código define los valores para las variables `` y ``. Establece el
valor de `` a 0 y `` a 'Cambio en productos y demandas del día'. */
// Definir valores para precio y descripción
$precio = 0; // Ejemplo: asumir que el precio es 0 si no se proporciona.
$descripcion = 'Cambio en productos y demandas del día'; // Ejemplo de descripción

// Recopilar la información de cambios realizados en demanda y productos.
$queryDemanda = "SELECT nombre, cantidad, descripcion FROM demanda WHERE DATE(fecha) = CURDATE()";
$queryProducto = "SELECT nombre, stock, precio, estado, promocion_idpromocion, categoria_idcategoria, familia_idfamilia, tipo_idtipo, demanda_iddemanda, fecha_modificacion FROM producto WHERE DATE(fecha_modificacion) = CURDATE()";


$resultadoDemanda = mysqli_query($con, $queryDemanda);
$resultadoProducto = mysqli_query($con, $queryProducto);

// Verifica si hubo algún error en la consulta de demanda.
if (!$resultadoDemanda) {
    echo json_encode(array('error' => "Error en la consulta de demanda: " . mysqli_error($con)));
    exit;
}

// Verifica si hubo algún error en la consulta de productos.
if (!$resultadoProducto) {
    echo json_encode(array('error' => "Error en la consulta de productos: " . mysqli_error($con)));
    exit;
}
/* Este bloque de código prepara el contenido del documento PDF. Comienza creando un título "Reporte de
Cambios". Luego, incluye cualquier cambio en la demanda, si lo hubiera. */

// Preparar el contenido para el PDF.
$contenido = "<h1>Reporte de Cambios</h1>";

// Incluir cambios en demanda si los hay
$contenido .= "<h2>Cambios en Demanda</h2>";
if (mysqli_num_rows($resultadoDemanda) > 0) {
    $contenido .= "<ul>";
    while ($demanda = mysqli_fetch_assoc($resultadoDemanda)) {
        $contenido .= "<li>Nombre: " . htmlspecialchars($demanda['nombre']) .
                       ", Cantidad: " . htmlspecialchars($demanda['cantidad']) .
                       ", Descripción: " . htmlspecialchars($demanda['descripcion']) . "</li>";
    }
    $contenido .= "</ul>";
} else {
    $contenido .= "<p>No hay cambios en demandas para el día de hoy.</p>";
}


// Incluir cambios en productos si los hay
/* El código genera una lista de cambios en los productos. */
$contenido .= "<h2>Cambios en Productos</h2>";
if (mysqli_num_rows($resultadoProducto) > 0) {
    $contenido .= "<ul>";
    while ($producto = mysqli_fetch_assoc($resultadoProducto)) {
        // Obtén información adicional de otras tablas
        $promocionInfo = obtenerInformacionAdicional($con, 'promocion', 'idpromocion', $producto['promocion_idpromocion'], ['nombre', 'estado']);
        $categoriaInfo = obtenerNombreAdicional($con, 'categoria', 'idcategoria', $producto['categoria_idcategoria']);
        $familiaInfo = obtenerNombreAdicional($con, 'familia', 'idfamilia', $producto['familia_idfamilia']);
        $tipoInfo = obtenerNombreAdicional($con, 'tipo', 'idtipo', $producto['tipo_idtipo']);
        $demandaInfo = obtenerInformacionAdicional($con, 'demanda', 'iddemanda', $producto['demanda_iddemanda'], ['nombre', 'estado']);

        $contenido .= "<li>Nombre: " . htmlspecialchars($producto['nombre']) .
                       ", Stock: " . htmlspecialchars($producto['stock']) .
                       ", Precio: " . htmlspecialchars($producto['precio']) .
                       ", Estado: " . htmlspecialchars($producto['estado']) .
                       ", Promocion: " . htmlspecialchars($promocionInfo['nombre']) . " - Estado: " . htmlspecialchars($promocionInfo['estado']) .
                       ", Categoria: " . htmlspecialchars($categoriaInfo) .
                       ", Familia: " . htmlspecialchars($familiaInfo) .
                       ", Tipo: " . htmlspecialchars($tipoInfo) .
                       ", Demanda: " . htmlspecialchars($demandaInfo['nombre']) . " - Estado: " . htmlspecialchars($demandaInfo['estado']) .
                       ", Fecha de Modificación: " . htmlspecialchars($producto['fecha_modificacion']) . "</li>";
    }
    $contenido .= "</ul>";
} else {
    $contenido .= "<p>No hay cambios en productos para el día de hoy.</p>";
}

/* Este código crea un documento PDF utilizando la biblioteca TCPDF en PHP. Luego guarda el archivo PDF
en el servidor, inserta un nuevo registro en la tabla "historial" de una base de datos y asocia el
archivo PDF con el registro insertado en la tabla "historial_pdf". Finalmente devuelve una respuesta
JSON con el ID del registro insertado en "historial_pdf" y la ruta al archivo PDF. */

// Crear una instancia de la clase TCPDF y configurar el documento.
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->writeHTML($contenido, true, 0, true, 0);

// Definir la ruta y el nombre del archivo PDF.
$dir = 'C:/xampp/htdocs/VENFARMA/pdfs/'; // Asegúrate de que esta carpeta tenga los permisos adecuados.
$filename = 'historial_' . date('YmdHis') . '.pdf';
$rutaCompletaPdf = $dir . $filename;

// Guardar el archivo en el servidor.
$pdf->Output($rutaCompletaPdf, 'F');

// Insertar un nuevo registro en `historial`
$queryInsertHistorial = "INSERT INTO historial (fecha, precio, descripcion) VALUES (NOW(), ?, ?)";
$stmtHistorial = $con->prepare($queryInsertHistorial);
$stmtHistorial->bind_param("is", $precio, $descripcion);
$stmtHistorial->execute();

$response = array();

// Verificar si el registro en `historial` fue exitoso
if ($stmtHistorial->affected_rows > 0) {
    $idHistorialGenerado = $stmtHistorial->insert_id;

    // Insertar la ruta del PDF y asociarla con el registro en `historial`
    $queryInsertHistorialPdf = "INSERT INTO historial_pdf (id_historial, ruta_pdf, fecha_creacion) VALUES (?, ?, NOW())";
    $stmtHistorialPdf = $con->prepare($queryInsertHistorialPdf);
    $stmtHistorialPdf->bind_param("is", $idHistorialGenerado, $rutaCompletaPdf);
    $stmtHistorialPdf->execute();

    // Verificar si el registro en `historial_pdf` fue exitoso
    if ($stmtHistorialPdf->affected_rows > 0) {
        // Devolver el ID del registro en `historial_pdf` y la ruta del PDF
        $response["idHistorialPdf"] = $stmtHistorialPdf->insert_id;
        $response["rutaPdf"] = $rutaCompletaPdf;
    } else {
        $response["error"] = "No se pudo insertar el registro en la tabla `historial_pdf`.";
    }

    $stmtHistorialPdf->close();
} else {
    $response["error"] = "No se pudo insertar el registro en la tabla `historial`.";
}

$stmtHistorial->close();

// Cerrar la conexión a la base de datos.
mysqli_close($con);

// Devolver la respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
