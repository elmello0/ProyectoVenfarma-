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
    <!-- Metadatos y referencias a hojas de estilo y scripts -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <?php
        // Inicio de sesión y verificación de autenticación del usuario
        session_start();

        // Verificar si el usuario está logueado y tiene un rol asignado
        if (!isset($_SESSION['role_name'])) {
            header('Location: login.php'); // Redireccionar al login si no está logueado
            exit();
        }

        $rolUsuario = $_SESSION['role_name']; // Obtener el rol del usuario
        ?>
    </header>

    <!-- Menú dinámico según el rol del usuario -->
    <header>
    <?php if ($rolUsuario == 'admin'): ?>
        <h3>Venfarma Demandas</h3>
        <!-- Enlaces para el administrador -->
        <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
        <a href="getEmpleados.php"><button>Empleados</button></a>
    <?php elseif ($rolUsuario == 'empleado'): ?>
        <h3>Venfarma Empleado</h3>
        <!-- Enlaces para el empleado -->
        <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
    <?php elseif ($rolUsuario == 'encargado'): ?>
        <h3>Venfarma Reabastecimientos</h3>
        <!-- Enlaces para el encargado -->
        <a href="menu_admin.php"><button>Menú</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
    <?php else: ?>
        <!-- Mensaje en caso de rol no reconocido -->
        <p>Rol no reconocido.</p>
    <?php endif; ?>
    </header>

    <!-- Contenedor para el gráfico de ECharts -->
    <div id="bar_chart" style="width: 100%; height: 500px;"></div>

    <!-- Botones para las acciones -->
    <div id="buttonsContainer" style="min-height: 50px;">
        <div id="buttons" style="display: none;">
            <button onclick="quitarDemanda()">Quitar</button>
            <button onclick="anadirDemanda()">Añadir</button>
            <button id="botonMostrarDescripcion" style="display: none;" onclick="mostrarDescripcion(selectedDemandName)">Mostrar Descripción</button>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    // Variables globales para almacenar información seleccionada y configurar el gráfico
    var selectedDemandName = null; // Almacena el nombre de la demanda seleccionada
    var selectedIndex = -1; // Índice seleccionado en el gráfico
    var myChart = echarts.init(document.getElementById('bar_chart')); // Inicializa el gráfico de ECharts

    // Datos para el gráfico obtenidos de PHP
    var datosDelGrafico = <?php echo $jsonParaGrafico; ?>;
    var nombres = datosDelGrafico.map(function(item) { return item[0]; }).slice(1);
    var cantidades = datosDelGrafico.map(function(item) { return item[1]; }).slice(1);

    // Configuración del gráfico
    var option = {
        title: { text: 'Demanda de Productos' },
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        xAxis: { type: 'category', data: nombres, axisLabel: { rotate: 45, interval: 0 } },
        yAxis: { type: 'value' },
        series: [{ name: 'Cantidad', type: 'bar', data: cantidades, barWidth: '60%' }]
    };
    myChart.setOption(option);

    // Ejecución una vez que el documento esté listo
    $(document).ready(function() {
        // Configuración del modal de descripción
        $('#modalDescripcion').modal({ backdrop: 'static', keyboard: true, show: false });

        // Manejo de clics en el gráfico
        myChart.on('click', function (params) {
            if (params.componentType === 'series') {
                if (selectedIndex !== params.dataIndex) {
                    highlightBar(params.dataIndex);
                    selectedIndex = params.dataIndex;
                    $('#buttons').show();
                    document.getElementById('botonMostrarDescripcion').style.display = 'block';
                    selectedDemandName = params.name;
                } else {
                    highlightBar(null);
                    selectedIndex = -1;
                    ocultarElementos();
                }
            } else {
                highlightBar(null);
                selectedIndex = -1;
                ocultarElementos();
            }
        });

        // Mostrar modal con descripción al hacer clic en el botón
        $('#botonMostrarDescripcion').click(function() {
            if (selectedDemandName) {
                document.getElementById('nombreBarraSeleccionada').innerText = selectedDemandName;
                mostrarModal('modalDescripcion')
                mostrarDescripcion(selectedDemandName);
            }
        });

        // Manejadores para cerrar el modal
        $('.cerrar, .modal-close').click(function() {
            $('#modalDescripcion').modal('hide');
        });

        // Remover el fondo del modal al cerrarlo
        $('#modalDescripcion').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
        });
    });

    // Función para resaltar o desresaltar una barra en el gráfico
    function highlightBar(index) {
        myChart.dispatchAction({
            type: index !== null ? 'highlight' : 'downplay',
            seriesIndex: 0,
            dataIndex: index
        });
    }
/**
 * Realiza una solicitud AJAX para obtener la descripción de una demanda y muestra la descripción en un modal.
 *
 * @param {string} nombreSeleccionado - El nombre de la demanda para la cual se obtendrá la descripción.
 * @returns {void}
 */
    function mostrarDescripcion(nombreSeleccionado) {
        $.ajax({
            url: 'obtenerDescripcionDemanda.php',
            type: 'GET',
            data: { nombre: nombreSeleccionado },
            success: function(respuesta) {
                document.getElementById('textoDescripcion').innerText = respuesta;
                $('#modalDescripcion').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener la descripción:', textStatus, errorThrown);
                alert('Error al obtener la descripción: ' + textStatus);
            }
        });
    }
/**
 * Cierra el modal que se muestra para la descripción de la demanda.
 *
 * @returns {void}
 */
    function cerrarModal() {
        $('#modalDescripcion').modal('hide');
    }
/**
 * Oculta varios elementos, incluidos los botones en una interfaz de usuario.
 *
 * @returns {void}
 */
    function ocultarElementos() {
    $('#buttons').hide();
    $('#botonMostrarDescripcion').hide();
    $('#botonQuitar').hide();
    $('#botonAnadir').hide();
}
    /**
 * Actualiza la visibilidad de los botones según el nombre de la demanda seleccionada.
 *
 * @param {string} nombre - El nombre de la demanda seleccionada.
 * @returns {void}
 */
    function handleDemandUpdate(nombre) {
        selectedDemandName = nombre;
        if (selectedDemandName) {
            $('#buttons').show(); 
        } else {
            $('#buttons').hide();
        }
    }
/**
 * Realiza una solicitud AJAX para obtener datos actualizados y actualiza un gráfico con esos datos.
 *
 * @returns {void}
 */
    function actualizarGrafico() {
    $.ajax({
        url: 'obtenerDatosGrafico.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data) {
            var nombres = data.map(function (item) { return item[0]; }).slice(1);
            var cantidades = data.map(function (item) {
                var cantidad = Math.floor(item[1]);
                return cantidad > 0 ? cantidad : 0.1;
            }).slice(1);

            myChart.setOption({
                xAxis: {
                    data: nombres
                },
                series: [{
                    data: cantidades
                }]
            });

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
 * Realiza una acción para quitar una demanda.
 *
 * @returns {void}
 */
function quitarDemanda() {
    if (selectedDemandName) {
        // Aquí debes obtener la cantidad que se va a quitar, ejemplo: 1
        var cantidadAQuitar = 1; // Esta cantidad puede variar según tu lógica de negocio

        $.ajax({
            url: 'quitarDemanda.php',
            type: 'POST',
            data: { nombre: selectedDemandName },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    actualizarGrafico();
                    agregarAlHistorial(cantidadAQuitar, selectedDemandName, false);
                } else if (data.deletePrompt) {
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
 * Realiza una acción para eliminar completamente una demanda.
 *
 * @returns {void}
 */
    function eliminarDemandaCompletamente() {
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
 * Realiza una acción para añadir una demanda.
 *
 * @returns {void}
 */
    function anadirDemanda() {
    if (selectedDemandName) {
        var cantidadAAnadir = 1; 

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
                    agregarAlHistorial(cantidadAAnadir, selectedDemandName, true);
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
 * Realiza una acción para eliminar una demanda.
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
    // Inicializa el historial de movimientos como un arreglo vacío
var historialMovimientos = [];

// Función para añadir un movimiento al historial
function agregarAlHistorial(cantidad, nombre, esAñadido) {
    // Determina la acción basada en si se añadió o quitó la cantidad
    var accion = esAñadido ? 'Añadido +' : 'Quitado -';
    // Construye el texto del movimiento
    var movimiento = accion + Math.abs(cantidad) + ' en ' + nombre;

    // Añade el movimiento al inicio del historial
    historialMovimientos.unshift(movimiento);
    // Asegúrate de que solo los últimos 5 movimientos estén en el historial
    historialMovimientos = historialMovimientos.slice(0, 5);
    // Actualiza la visualización del historial
    mostrarHistorial();
}

// Función para mostrar el historial en la página
function mostrarHistorial() {
    var lista = $('#listaMovimientos');
    lista.empty(); // Limpia la lista actual
    historialMovimientos.forEach(function(mov) {
        // Crea un elemento de lista para cada movimiento
        lista.append('<li>' + mov + '</li>');
    });
}
/**
     * Vincula un evento de clic al botón 'btnEditarDescripcion'. Cuando se hace clic en el botón,
     * obtiene la descripción actual del elemento 'textoDescripcion', la coloca en el área de texto
     * 'editarTextoDescripcion' para su edición, y muestra el modal 'modalEditarDescripcion' para la edición.
     */
    $('#btnEditarDescripcion').click(function() {
        var descripcionActual = $('#textoDescripcion').text(); // Obtiene la descripción actual
        $('#editarTextoDescripcion').val(descripcionActual); // Coloca la descripción en el área de texto
        $('#modalEditarDescripcion').show(); // Muestra el modal para editar
    });

    /**
     * Función para cerrar el modal 'modalEditarDescripcion'.
     * Se llama para ocultar el modal una vez que la edición se ha completado o cancelado.
     */
    function cerrarModalEditar() {
        $('#modalEditarDescripcion').hide();
    }

    /**
     * Función que guarda la descripción editada. Envía una solicitud AJAX al servidor para actualizar la
     * descripción en la base de datos. En caso de éxito, actualiza la descripción mostrada en el modal,
     * recarga el gráfico y cierra el modal de edición.
     */
    function guardarDescripcionEditada() {
        var nuevaDescripcion = $('#editarTextoDescripcion').val(); // Obtiene la nueva descripción del área de texto

        $.ajax({
            url: 'editar_desc_demanda.php', // URL del script PHP para procesar la actualización
            type: 'POST',
            data: {
                nombre: selectedDemandName, // Nombre de la demanda seleccionada
                nuevaDescripcion: nuevaDescripcion // Nueva descripción a guardar
            },
            success: function(response) {
                $('#textoDescripcion').text(nuevaDescripcion); // Actualiza la descripción en la interfaz
                actualizarGrafico(); // Llama a función para actualizar el gráfico
                cerrarModalEditar(); // Cierra el modal de edición
            },
            error: function() {
                alert('Error al guardar los cambios'); // Notifica al usuario en caso de error
            }
        });
    }

    /**
     * Código que se ejecuta una vez que el documento está completamente cargado.
     * Vuelve a vincular el evento de clic al botón 'btnEditarDescripcion' para manejar la edición de la descripción.
     * Este código parece duplicar la funcionalidad ya definida fuera de $(document).ready, y podría ser redundante.
     */
    $(document).ready(function() {
        $('#btnEditarDescripcion').click(function() {
            var descripcionActual = $('#textoDescripcion').text();
            $('#editarTextoDescripcion').val(descripcionActual);
            $('#modalEditarDescripcion').show();
        });
    });
</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos y referencias a hojas de estilo -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Admin</title>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
</head>
<body>
    <!-- Contenedor Principal del Menú Admin -->
    <div id="contenedorPrincipal">
        <!-- Contenedor del Gráfico de Historial y Listado de Movimientos -->
        <div id="contenedorGraficoHistorial">
            <div id="bar_chart"></div> <!-- Espacio para el gráfico de barras -->
            <div id="historialMovimientos">
                <h3>Historial de Movimientos</h3>
                <ul id="listaMovimientos"></ul> <!-- Lista para mostrar movimientos -->
            </div>
        </div>
        <button id="btnAgregarDemanda">Agregar Demanda</button> <!-- Botón para agregar demanda -->
    </div>

    <!-- Botón oculto para mostrar descripción -->
    <button id="botonMostrarDescripcion" style="display: none;">Mostrar Descripción</button>
    
    <!-- Modal para mostrar la descripción de una demanda -->
    <div id="modalDescripcion" class="modal" style="display:none;">
        <div class="modal-content">
            <h4>Descripción de la Demanda</h4>
            <p id="nombreBarra"><b>de:</b> <span id="nombreBarraSeleccionada"></span></p>
            <p id="textoDescripcion">Aquí va la descripción...</p>
            <button id="btnEditarDescripcion">Editar</button> <!-- Botón para editar descripción -->
        </div>
        <div class="modal-footer">
            <button class="modal-close">Cerrar</button> <!-- Botón para cerrar el modal -->
        </div>
    </div>

    <!-- Modal para editar la descripción de una demanda -->
    <div id="modalEditarDescripcion" class="modal" style="display:none;">
        <div class="modal-content">
            <h4>Editar Descripción de la Demanda</h4>
            <textarea id="editarTextoDescripcion"></textarea> <!-- Área de texto para edición -->
            <button onclick="guardarDescripcionEditada()">Guardar Cambios</button> <!-- Botón para guardar cambios -->
        </div>
        <div class="modal-footer">
            <button class="modal-close">Cerrar</button> <!-- Botón para cerrar el modal -->
        </div>
    </div>

    <!-- Modal para registrar una nueva demanda -->
    <div id="miModal" class="modal">
        <div class="modal-contenido">
            <span class="cerrar">&times;</span> <!-- Botón para cerrar el modal -->
            <form id="formularioDemanda">
                <!-- Formulario para registrar demanda -->
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
</body>
</html>


    <script>
    /**
     * Hace visibles los botones al cambiar su propiedad de visibilidad.
     */
    function mostrarBotones() {
        document.getElementById('buttons').style.visibility = 'visible';
    }
    /**
     * Oculta los botones al cambiar su propiedad de visibilidad.
     */
    function ocultarBotones() {
        document.getElementById('buttons').style.visibility = 'hidden';
    }

    // Referencia al modal en el documento
    var modal = document.getElementById("miModal");

    // Referencia al botón que activa el modal
    var btn = document.getElementById("btnAgregarDemanda");

    // Referencia al elemento que cierra el modal (botón con clase 'cerrar')
    var span = document.getElementsByClassName("cerrar")[0];

    /**
     * Manejador de eventos para abrir el modal.
     * Se activa al hacer clic en el botón 'btnAgregarDemanda'.
     */
    btn.onclick = function() {
        modal.style.display = "block";
    }

    /**
     * Manejador de eventos para cerrar el modal.
     * Se activa al hacer clic en el elemento con clase 'cerrar'.
     */
    span.onclick = function() {
        modal.style.display = "none";
    }

    /**
     * Manejador de eventos para cerrar el modal al hacer clic fuera de él.
     * Se activa al hacer clic en cualquier lugar fuera del modal.
     */
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    /**
     * Manejador de eventos para el formulario de demanda.
     * Evita la recarga de la página y envía los datos mediante una solicitud AJAX.
     * Muestra alertas basadas en la respuesta del servidor.
     */
    document.getElementById('formularioDemanda').onsubmit = function(event) {
        event.preventDefault();

        $.ajax({
            url: 'guardarDemanda.php', // URL del script del servidor que procesa la demanda
            type: 'POST',
            data: $(this).serialize(), // Envía los datos del formulario
            success: function(response) {
                // Procesa la respuesta del servidor
                var data = JSON.parse(response);
                if (data.success) {
                    alert('Demanda agregada correctamente');
                    actualizarGrafico(); // Actualiza el gráfico tras agregar la demanda
                } else {
                    alert('Error al agregar demanda: ' + data.error);
                }
            },
            error: function(xhr, status, errorThrown) {
                // Maneja errores de la solicitud AJAX
                alert('Error al realizar la solicitud: ' + errorThrown);
            }
        });
    };
</script>

</body>

<style>

/* Estilos para el contenedor de botones */
#buttonsContainer {
    min-height: 50px; /* Altura mínima para mantener un espacio consistente */
}

/* Estilos para el modal */
.modal {
    display: none; /* Oculto por defecto para que se muestre solo cuando se activa */
    position: fixed; /* Fijo en la pantalla para permanecer visible al desplazar */
    z-index: 1000; /* Asegura que se muestre sobre otros elementos */
    left: 50%;
    top: 50%;
    width: 40%; /* Ancho del modal como porcentaje de la ventana del navegador */
    overflow: auto; /* Permite el desplazamiento si el contenido es más grande que el modal */
    background-color: white;
    transform: translate(-50%, -50%); /* Centra el modal en la pantalla */
    border-radius: 5px; /* Bordes redondeados para una estética moderna */
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); /* Sombra para un efecto de profundidad */
    max-height: 90vh; /* Limita la altura máxima del modal */
    overflow-y: auto; /* Añade desplazamiento vertical si es necesario */
}

/* Estilos para el contenido del modal */
.modal-contenido {
    background-color: #fefefe;
    margin: 5% auto; /* Centra el contenido verticalmente dentro del modal */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Controla el ancho del contenido dentro del modal */
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    border-radius: 5px;
    position: relative; /* Permite posicionar elementos absolutamente dentro */
}

/* Estilos para inputs, selects y textareas dentro del modal */
.modal-contenido input[type="text"],
.modal-contenido input[type="datetime-local"],
.modal-contenido select,
.modal-contenido textarea {
    width: 90%; /* Ajusta el ancho a un poco menos que el contenedor para márgenes */
    padding: 10px;
    margin: 10px 0; /* Espaciado vertical para separar elementos */
    border: 1px solid #ccc;
    border-radius: 4px; /* Bordes redondeados para una mejor estética */
}

/* Estilos para los labels dentro del modal */
.modal-contenido label {
    display: block; /* Cada label en su propia línea */
    margin-top: 10px; /* Espaciado antes del label */
    margin-bottom: 5px; /* Espaciado después del label */
}

/* Estilos para el botón de 'Registrar Demanda' */
.modal-contenido input[type="submit"] {
    width: auto;
    padding: 10px 20px;
    margin-top: 15px;
    background-color: #4CAF50; /* Color verde estándar para acciones positivas */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer; /* Indica que el botón es clickeable */
}

/* Cambios de estilo al pasar el mouse sobre el botón 'Registrar Demanda' */
.modal-contenido input[type="submit"]:hover {
    background-color: #45a049;
}

/* Estilos para el botón de cerrar el modal */
.cerrar {
    position: absolute;
    top: 10px;
    right: 15px;
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
}

/* Estilos al interactuar con el botón de cerrar */
.cerrar:hover,
.cerrar:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Estilos para el pie del modal */
.modal-footer {
    text-align: right;
}

/* Estilos para el botón de cierre en el pie del modal */
.modal-close {
    padding: 10px 20px;
    background-color: #f44336; /* Color rojo para acciones de cierre o negativas */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Cambios de estilo al pasar el mouse sobre el botón de cierre */
.modal-close:hover {
    background-color: #d32f2f;
}

/* Estilos para el fondo detrás del modal */
.modal-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente para enfocar en el modal */
    z-index: 998; /* Menor que el modal para que aparezca detrás */
}


/* ESTILOS DE HISTORIAL DE MOVIMIENTOS  */

/* Estilos para el contenedor del gráfico de historial */
#contenedorGraficoHistorial {
    position: relative; /* Permite que los elementos absolutos dentro se posicionen relativo a este contenedor */
}

/**
 * Estilos para el elemento que muestra el historial de movimientos.
 *
 * - El ancho se establece en 60% para proporcionar espacio suficiente para el contenido,
 *   pero no ocupa el 100% del ancho disponible, para evitar un diseño que se extienda demasiado.
 * - El margen superior de 20px separa visualmente este elemento de otros contenidos arriba,
 *   y el 'auto' en los márgenes laterales centra horizontalmente el elemento en su contenedor.
 * - Se evita el uso de 'position: absolute' a menos que sea estrictamente necesario, para mantener
 *   un flujo de documento normal y prevenir problemas de posicionamiento.
 */
#historialMovimientos {
    width: 60%;
    margin: 20px auto 0 auto;
}

/**
 * Estilos para el contenedor de botones.
 *
 * - Posicionado absolutamente en la parte inferior del contenedor de referencia,
 *   lo que permite que este elemento se ubique de manera fija en relación con su contenedor padre.
 * - El 'bottom' de 50px levanta el contenedor desde la parte inferior, creando espacio
 *   para otros elementos como un botón de "Agregar Demanda".
 * - El ancho se extiende al 100% para abarcar todo el ancho del contenedor padre.
 * - El 'z-index' de 10 asegura que el contenedor se muestre por encima de otros elementos
 *   en la misma área, como puede ser un gráfico.
 */
#buttonsContainer {
    position: absolute;
    bottom: 50px;
    left: 0;
    width: 100%;
    z-index: 10;
}

/**
 * Estilos para el botón 'Agregar Demanda'.
 *
 * - 'Position: fixed' mantiene el botón en una posición fija en la pantalla,
 *   lo que significa que permanecerá en el mismo lugar incluso si el usuario desplaza la página.
 * - Colocado específicamente en la parte inferior derecha de la pantalla con 'bottom' de 10px
 *   y 'right' de 20px, para ser fácilmente accesible pero sin obstruir otros elementos.
 */
#btnAgregarDemanda {
    position: fixed;
    bottom: 10px;
    right: 20px;
}



</style>
</html>