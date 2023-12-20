
<?php
/*<!--Este código   es un script PHP que se conecta a una base de datos MySQL y recupera datos de una
tabla llamada "demanda". Luego procesa los datos para crear una matriz adecuada para generar un
gráfico utilizando Google Charts. El script convierte la matriz al formato JSON y la almacena en la
variable . Si hay un error en la conexión de la base de datos, se almacena un
mensaje de error en . Finalmente, se cierra la conexión a la base de datos. -->*/

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

try {
    // Establecer la conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener la suma de demandas por nombre
    $query = "SELECT nombre, SUM(cantidad) as cantidad FROM demanda GROUP BY nombre";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Construir un array con los datos para el gráfico
    $datosParaGrafico = [];
    $datosParaGrafico[] = ['Nombre', 'Cantidad']; // Encabezados para Google Charts
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $datosParaGrafico[] = [$row['nombre'], (int)$row['cantidad']];
    }

    // Convertir los datos del gráfico a JSON para el front-end
    $jsonParaGrafico = json_encode($datosParaGrafico);

} catch (PDOException $e) {
    // Manejar errores de la conexión a la base de datos
    $jsonParaGrafico = json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
</head>

<body>
    <header>
    <h4>Demandas</h4>
        <a href="menu_admin.php"><button>Menú Admin</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
        <a href="getEmpleados.php"><button>Empleados</button></a>
    </header>
    <div>
    <div>
        <h1> </h1>
        <h1> </h1>
        <h1> </h1>
    </div>
</div>
</body>
</html>
<div>
    <div>

    </div>
</div>
<head>

<!--Este código   crea un modal en HTML usando clases CSS. El modal contiene un formulario con
campos de entrada para nombre, fecha, descripción, estado y cantidad. El formulario también tiene un
botón de enviar para registrar una demanda. -->

<!-- El Modal -->

<div id="miModal" class="modal">
        <!-- Contenido del modal -->
        <div class="modal-contenido">
            <span class="cerrar">&times;</span>
            <form id="formularioDemanda">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre"><br><br>
                <label for="fecha">Fecha:</label>
                <input type="datetime-local" name="fecha" required><br><br>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"></textarea><br><br>
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="si">Si</option>
                    <option value="no">No</option>
                </select><br><br>
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad"><br><br>
                <input type="submit" value="Registrar Demanda">
            </form>
        </div>
    </div>

    <title>Gráfico de Demandas</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

/* Este código   utiliza la biblioteca de Google Charts para crear un gráfico de barras. Está
cargando los paquetes necesarios y configurando una función de devolución de llamada para dibujar el
gráfico. La función `drawChart` toma datos para el gráfico y los formatea. Luego establece varias
opciones para el gráfico, como el título, los colores, las etiquetas de los ejes y la animación.
Finalmente, crea el gráfico utilizando los datos y opciones especificados. */
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        var selectedDemandName = null;

        function drawChart(datosDelGrafico) {
    var data = google.visualization.arrayToDataTable(datosDelGrafico || <?php echo $jsonParaGrafico; ?>);

    var formatter = new google.visualization.NumberFormat({ 
        fractionDigits: 0
    });
    formatter.format(data, 1);


    var options = {

        title: 'Demanda de Productos', 
        titleTextStyle: {
            color: '#4a4a4a',
            fontSize: 18,
            bold: true
        },

        colors: ['#1b9e77', '#d95f02', '#7570b3'],

        vAxis: {
            title: 'Cantidad',
            titleTextStyle: {
                color: '#4a4a4a',
                fontSize: 14,
                bold: true
            },
            minValue: 0,
            viewWindow: {
                min: 0
            },
            format: '#',
            textStyle: {
                fontSize: 12
            }
        },

        hAxis: {
            textStyle: {
                fontSize: 12
            }
        },

        legend: {
            position: 'none'
        },
 
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out'
        },

        chartArea: {
            width: '80%',
            height: '70%'
        }
    };

/* Este código   crea un gráfico de barras utilizando la biblioteca de Google Charts en PHP. Luego
agrega un detector de eventos al gráfico para manejar la selección de una barra. Cuando se
selecciona una barra, recupera el valor de la barra seleccionada y realiza algunas acciones, como
mostrar botones y mostrar una descripción. */
    //Gráfico de Barras
    var chart = new google.charts.Bar(document.getElementById('bar_chart'));
    chart.draw(data, google.charts.Bar.convertOptions(options));

    //seleccion del grafico de barras
    google.visualization.events.addListener(chart, 'select', function() {
    var selection = chart.getSelection();
    if (selection.length) {
        var row = selection[0].row;
        selectedDemandName = data.getValue(row, 0);
        // botones y botón de descripción.
        $('#buttons').show();
        document.getElementById('botonMostrarDescripcion').style.display = 'block';
    } else {
        // No hay selección, por lo que se ocultan los botones.
        $('#buttons').hide();
        document.getElementById('botonMostrarDescripcion').style.display = 'none';
    }
    });
}

/**
 * Este código   es una función PHP que realiza una solicitud AJAX para obtener una descripción de
 * una demanda y la muestra en un modal.
 */
function mostrarDescripcion() {
    $.ajax({
        url: 'obtenerDescripcionDemanda.php',
        type: 'GET',
        data: { nombre: selectedDemandName },
        success: function(respuesta) {
            document.getElementById('textoDescripcion').innerText = respuesta;
            document.getElementById('modalDescripcion').style.display = "block";
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al obtener la descripción:', textStatus, errorThrown);
            alert('Error al obtener la descripción: ' + textStatus);
        }
    });
}

function cerrarModal() {
  $('#modalDescripcion').hide();
}

        function handleDemandUpdate(nombre) {
    selectedDemandName = nombre;
    if (selectedDemandName) {
        $('#buttons').show(); 
    } else {
        $('#buttons').hide();
    }
}
/**
 * La función "actualizarGrafico" realiza una solicitud AJAX para obtener datos actualizados para un
 * gráfico y luego llama a la función "drawChart" para mostrar el gráfico actualizado.
 */
function actualizarGrafico() {
    $.ajax({
        url: 'obtenerDatosGrafico.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data) {
            for (var i = 1; i < data.length; i++) {
                if (data[i][1] === 0.001) {
                    data[i][1] = 0;
                }
            }
            drawChart(data);

            if (selectedDemandName) {
                handleDemandUpdate(selectedDemandName);
            }
        },
        error: function(xhr, status, error) {
            alert("Error al obtener los datos actualizados: " + error);
        }
    });
}
/**
 * La función "quitarDemanda" envía una solicitud para disminuir la cantidad de una demanda, y si la
 * cantidad alcanza un cierto umbral, solicita al usuario confirmación para eliminar la demanda por
 * completo.
 */

function quitarDemanda() {
    if (selectedDemandName) {
        // solicitud para disminuir la cantidad
        $.ajax({
            url: 'quitarDemanda.php',
            type: 'POST',
            data: { nombre: selectedDemandName },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    actualizarGrafico();
                } else if (data.deletePrompt) {
                    // Si la cantidad llega a 0.100, muestra la confirmación de eliminación
                    if (confirm(data.message)) {
                        eliminarDemandaCompletamente();
                    }
                } else {
                    alert("Error: " + data.error);
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("Error al intentar disminuir la demanda: " + errorThrown);
            }
        });
    }
}
/**
 * La función "eliminarDemandaCompletamente" envía una solicitud a un archivo PHP para eliminar una
 * demanda y actualiza un gráfico si la eliminación es exitosa.
 */
function eliminarDemandaCompletamente() {
    // Realizar una solicitud para eliminar la demanda completamente
    $.ajax({
        url: 'comprobarYEliminarDemanda.php', 
        type: 'POST',
        data: { nombre: selectedDemandName },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                actualizarGrafico();
            } else {
                alert("Error al eliminar la demanda: " + data.error);
            }
        },
        error: function(xhr, status, errorThrown) {
            alert("Error al intentar eliminar la demanda: " + errorThrown);
        }
    });
}


/**
 * La función "anadirDemanda" envía una solicitud POST a "anadirDemanda.php" con el nombre de la
 * demanda seleccionada como datos y maneja las respuestas de éxito y error.
 */
function anadirDemanda() {
    if (selectedDemandName) {
        $.ajax({
            url: 'anadirDemanda.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: { nombre: selectedDemandName },
            success: function(data) {
                if(data.success) {
                    handleDemandUpdate(selectedDemandName); 
                    actualizarGrafico();
                } else {
                    alert("Error: " + data.error);
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("Error al añadir demanda: " + errorThrown);
            }
        });
    }
}

/**
 * La función `eliminarDemanda()` envía una solicitud AJAX a un archivo PHP para eliminar una demanda y
 * actualiza la visualización y el gráfico de la demanda en consecuencia.
 */
function eliminarDemanda() {
    if (selectedDemandName) {
        $.ajax({
            url: 'eliminarDemanda.php',
            type: 'POST',
            data: { nombre: selectedDemandName },
            success: function(response) {
                var data = JSON.parse(response);
                if(data.success) {
                    handleDemandUpdate(null); 
                    actualizarGrafico();
                } else {
                    alert("Error: " + data.error);
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("Error al eliminar demanda: " + errorThrown);
            }
        });
    }
}

/**
 * La función vincula un evento de clic a un botón, que cuando se hace clic, llena un área de texto con
 * la descripción actual y muestra un modo para editar la descripción.
 */
$('#btnEditarDescripcion').click(function() {
    // Rellenar el textarea con la descripción actual
    var descripcionActual = $('#textoDescripcion').text();
    $('#editarTextoDescripcion').val(descripcionActual);

    // Mostrar el modal de edición
    $('#modalEditarDescripcion').show();
});

function cerrarModalEditar() {
    $('#modalEditarDescripcion').hide();
}

/**
 * Este código es una función PHP que guarda una descripción editada enviando una solicitud AJAX a un
 * script PHP, actualizando la descripción en el modal y recargando un gráfico.
 */
function guardarDescripcionEditada() {
    var nuevaDescripcion = $('#editarTextoDescripcion').val();

    $.ajax({
        url: 'editar_desc_demanda.php', // Reemplazar con tu script de actualización
        type: 'POST',
        data: {
            nombre: selectedDemandName,
            nuevaDescripcion: nuevaDescripcion
        },
        success: function(response) {
            // Actualizar la descripción en el modal original y recargar el gráfico
            $('#textoDescripcion').text(nuevaDescripcion);
            actualizarGrafico();
            cerrarModalEditar();
        },
        error: function() {
            alert('Error al guardar los cambios');
        }
    });
}
$(document).ready(function() {
    $('#btnEditarDescripcion').click(function() {
        var descripcionActual = $('#textoDescripcion').text();
        $('#editarTextoDescripcion').val(descripcionActual);
        $('#modalEditarDescripcion').show();
    });
});

    </script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">



    <style>
/* Este código   define los estilos CSS para un modal. El modal es una ventana emergente que se
muestra en la parte superior de la página actual. Los estilos definen la apariencia y la posición
del modal, incluido su tamaño, color de fondo, borde y botón de cierre. El modal se centra en la
página usando la propiedad de transformación. El modal también tiene una sección de pie de página
con un botón de cerrar. */
        /* Estilo para el modal */
        .modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 50%; 
    top: 50%; 
    width: 40%; 
    height: 50%; 
    overflow: auto; 
    background-color: white; 
    transform: translate(-50%, -50%); 
}

        .modal-contenido {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
        }

        .cerrar {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .cerrar:hover,
        .cerrar:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        

.modal-footer {
  text-align: right;
}

.modal-close {
  padding: 10px 20px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.modal-close:hover {
  background-color: #d32f2f;
}
    </style>



</head>
<!--- Este código   crea una página web con un gráfico de barras y botones. También incluye
funcionalidad para agregar y eliminar demanda, mostrar y editar descripciones de demanda usando
modales. ---->
<body>
    <div id="bar_chart" style="width: 100%; height: 500px;"></div>
    <div id="buttons" style="display: none;">
        <button onclick="quitarDemanda()">Quitar</button>
        <button onclick="anadirDemanda()">Añadir</button>
    </div>
    <button id="btnAgregarDemanda">Agregar Demanda</button>
    <!-- Botón que se muestra para ver la descripción de la demanda seleccionada -->
    <button id="botonMostrarDescripcion" style="display: none;" onclick="mostrarDescripcion(selectedDemandName)">Mostrar Descripción</button>
    <div id="modalDescripcion" class="modal" style="display:none;">
    <div class="modal-content">
        <h4>Descripción de la Demanda</h4>
        <p id="textoDescripcion">Aquí va la descripción...</p>
        <!-- Botón para editar la descripción -->
        <button id="btnEditarDescripcion">Editar</button>
    </div>
    <div class="modal-footer">
        <button onclick="cerrarModal()" class="modal-close">Cerrar</button>
    </div>
    <!-- Modal para Editar Descripción -->
<div id="modalEditarDescripcion" class="modal" style="display:none;">
    <div class="modal-content">
        <h4>Editar Descripción de la Demanda</h4>
        <textarea id="editarTextoDescripcion"></textarea>
        <button onclick="guardarDescripcionEditada()">Guardar Cambios</button>
    </div>
    <div class="modal-footer">
        <button onclick="cerrarModalEditar()" class="modal-close">Cerrar</button>
    </div>
</div>

<script>
/* Este código   implementa una funcionalidad modal en PHP. Crea un elemento modal con el id
"miModal" y un elemento botón con el id "btnAgregarDemanda". Cuando se hace clic en el botón, se
muestra el modal. Este código también incluye una funcionalidad para cerrar el modal cuando el usuario
hace clic en el botón "x" o en cualquier lugar fuera del modal. */
        // Obtener el modal
var modal = document.getElementById("miModal");

// Obtener el botón que abre el modal
var btn = document.getElementById("btnAgregarDemanda");

// Obtener el elemento <x> que cierra el modal
var span = document.getElementsByClassName("cerrar")[0];

// Cuando el usuario haga clic en el botón, abrir el modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// Cuando el usuario haga clic en <x>, cerrar el modal
span.onclick = function() {
  modal.style.display = "none";
}

// Cuando el usuario haga clic en cualquier lugar fuera del modal, cerrarlo
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

/* Este código   está escrito en PHP y JavaScript. Está manejando el envío de un formulario para
agregar demandas. */
// formulario para agregar demandas
document.getElementById('formularioDemanda').onsubmit = function(event) {
    event.preventDefault();

    $.ajax({
        url: 'guardarDemanda.php', 
        type: 'POST',
        data: $(this).serialize(), // Envía los datos del formulario
        success: function(response) {

            var data = JSON.parse(response);
            if(data.success) {
                alert('Demanda agregada correctamente');
                actualizarGrafico();
            } else {
                alert('Error al agregar demanda: ' + data.error);
            }
        },
        error: function(xhr, status, errorThrown) {
            alert('Error al realizar la solicitud: ' + errorThrown);
        }
    });
};


    </script>
    
</body>

</html>