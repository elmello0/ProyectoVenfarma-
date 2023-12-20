
<?php
/*<!-- El código es un script PHP que maneja la descarga de un archivo PDF. Aquí hay un desglose de lo que
hace: -->*/

// descargar_pdf.php

// Asumiendo que tienes una sesión iniciada y que en ella se almacena el ID del usuario
session_start();

// Verifico si el usuario está logueado. Si no, lo redirijo a la página de login.
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Incluye tu archivo de conexión a la base de datos si es necesario
include 'conexion.php';

// Verificamos si el ID del archivo está establecido
if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // Prepara la consulta para obtener la ruta del archivo
    $stmt = $con->prepare("SELECT ruta_pdf FROM historial_pdf WHERE id_pdf = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileRow = $result->fetch_assoc();

    // Verifica si el archivo existe en la base de datos
    if ($fileRow) {
        $filePath = $fileRow['ruta_pdf'];

        // Verificar que el archivo existe y es accesible en el sistema de archivos
        if (file_exists($filePath) && is_readable($filePath)) {
            // Establece los encabezados apropiados para el archivo de descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'. basename($filePath) .'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Limpia el sistema de buffers de salida
            flush(); 

            // Lee el archivo y envía al navegador
            readfile($filePath);

            // Termina la ejecución del script
            exit;
        } else {
            // El archivo no existe o no se puede leer
            echo 'Error: El archivo no existe o no se pudo leer.';
        }
    } else {
        // El archivo no existe en la base de datos
        echo 'Error: No se encontró el archivo en la base de datos.';
    }

    $stmt->close();
    $con->close();
} else {
    // El ID del archivo no se pasó
    echo 'Error: No se proporcionó el identificador del archivo.';
}
?>
