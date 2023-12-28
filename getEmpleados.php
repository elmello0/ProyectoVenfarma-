
<?php


/* <!-- Este código   es un script PHP que se conecta a una base de datos MySQL y recupera datos de
varias tablas. -->*/


// Parámetros de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los roles
$sql_roles = "SELECT idrol, nombre FROM rol";
$result_roles = $conn->query($sql_roles);
$roles = $result_roles->fetch_all(MYSQLI_ASSOC);

// Consulta SQL para obtener los cargos
$sql_cargos = "SELECT idcargo, nombre FROM cargo";
$result_cargos = $conn->query($sql_cargos);
$cargos = $result_cargos->fetch_all(MYSQLI_ASSOC);
// Consulta SQL para obtener los datos de los empleados
$sql = "SELECT e.idempleado, e.rut, e.nombre, e.apellido, r.nombre as nombre_rol, c.nombre as nombre_cargo 
        FROM empleado e 
        JOIN rol r ON e.rol_idrol = r.idrol 
        JOIN cargo c ON e.cargo_idcargo = c.idcargo";

$result = $conn->query($sql);


// Cerrar conexión
$conn->close();
?>

<!-- Este código   es un archivo PHP que crea una página web para agregar empleados. Incluye marcado
HTML para la estructura de la página, así como código PHP que no se muestra en el fragmento
proporcionado. Es probable que el código PHP sea responsable de manejar los envíos de formularios e
interactuar con una base de datos para agregar nuevos registros de empleados. La página también
incluye una sección de encabezado con enlaces de navegación a otras páginas relacionadas con
administración, productos, demandas, historial y empleados.  -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Empleado</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
</head>
<header>
    <?php
    session_start();

    // Verificar si el usuario está logueado y tiene un rol asignado
    if (!isset($_SESSION['role_name'])) {
        header('Location: login.php'); // Redireccionar al login si no está logueado
        exit();
    }

    $rolUsuario = $_SESSION['role_name']; // Obtener el rol del usuario
    ?>
<header>

<!-- Menú dinámico según el rol -->
<?php if ($rolUsuario == 'admin'): ?>
  <h3>Venfarma
    Empleados</h3>
  </h3>
    <a href="menu_admin.php"><button>Menú</button></a>
    <a href="getProductos.php"><button>Productos</button></a>
    <a href="getDemandas.php"><button>Demandas</button></a>
    <a href="getHistorial.php"><button>Historial</button></a>
    <a href="getEmpleados.php"><button>Empleados</button></a>
<?php elseif ($rolUsuario == 'empleado'): ?>
  <h3>Venfarma
    Empleado</h3>
  </h3>
    <a href="menu_admin.php"><button>Menú</button></a>
    <a href="getProductos.php"><button>Productos</button></a>
    <a href="getDemandas.php"><button>Demandas</button></a>
    <a href="getHistorial.php"><button>Historial</button></a>
<?php elseif ($rolUsuario == 'encargado'): ?>
  <h3>Venfarma
    Reabastecimientos</h3>
  </h3>
  <a href="menu_admin.php"><button>Menú</button></a>
    <a href="getProductos.php"><button>Productos</button></a>
    <a href="getDemandas.php"><button>Demandas</button></a>
    <a href="getHistorial.php"><button>Historial</button></a>
<?php else: ?>
    <p>Rol no reconocido.</p>
<?php endif; ?>
</header>
</header>
<div>
    <h1> </h1>
</div>
<body>


<!-- Este código   es un fragmento de código PHP que genera una tabla de empleados. Primero
comprueba si hay empleados en el conjunto de resultados. Si los hay, crea una tabla HTML con
columnas para ID, RUT, Nombre, Apellido, Rol y Puesto. Para cada empleado en el conjunto de
resultados, crea una fila de la tabla con la información del empleado y dos botones: "Editar" y
"Eliminar". Si no hay empleados en el conjunto de resultados, muestra "0 resultados".  -->

<!-- Botón para abrir el modal de añadir empleado -->
<button onclick="openAddEmployeeModal()">Añadir Empleado</button>
<div>
    <input type="text" id="terminoBusqueda" placeholder="Buscar empleado...">
    <button onclick="buscarEmpleado()">Buscar</button>
    <button onclick="restablecerVista()">X</button>
</div>

<div id="tablaEmpleados">
    <!-- Tabla de empleados existente -->
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID Empleado</th><th>RUT</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Cargo</th><th>Acciones</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["idempleado"]."</td>
                    <td>".$row["rut"]."</td>
                    <td>".$row["nombre"]."</td>
                    <td>".$row["apellido"]."</td>
                    <td>".$row["nombre_rol"]."</td>
                    <td>".$row["nombre_cargo"]."</td>
                    <td>
                    <button onclick='editarEmpleado(" . json_encode($row) . ")'>Editar</button>
                    <button onclick='eliminarEmpleado(" . $row["idempleado"] . ")'>Eliminar</button>
                    </td>
                    </tr>";
        }
        echo "</table>";
    } else {
        echo "0 resultados";
    }
    ?>
</div>


<!-- Este código   crea una ventana modal para agregar un empleado. El modal está oculto por defecto
(`style="display:none;"`) y se coloca en el centro de la pantalla (`position:fixed; top:20%;
left:50%; transform:translate(-50% , -50%);`).  -->

<!-- Modal para añadir empleado -->
<div id="addEmployeeModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Empleado</h2>
    <form id="addEmployeeForm">
        RUT: <input type="text" name="rut" required><br>
        Nombre: <input type="text" name="nombre" required><br>
        Apellido: <input type="text" name="apellido" required><br>
        Rol: 
        <select name="rol_idrol">
            <?php foreach($roles as $rol): ?>
                <option value="<?php echo $rol['idrol']; ?>">
                    <?php echo htmlspecialchars($rol['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        Cargo: 
        <select name="cargo_idcargo">
            <?php foreach($cargos as $cargo): ?>
                <option value="<?php echo $cargo['idcargo']; ?>">
                    <?php echo htmlspecialchars($cargo['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Añadir Empleado">
    </form>
    <button onclick="closeAddEmployeeModal()">Cerrar</button>
</div>

<!--Este código   crea una ventana modal para editar un empleado. Es un formulario que permite al
usuario ingresar la información del empleado como RUT, nombre y rol. También incluye menús
desplegables para seleccionar el rol y puesto del empleado. Este código está escrito en PHP y utiliza
un bucle foreach para completar los menús desplegables con opciones de matrices ( y ).
La ventana modal está inicialmente oculta y se puede cerrar haciendo clic en el botón "Cerrar". -->

<!-- Modal para editar empleado -->
<div id="editEmployeeModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Editar Empleado</h2>
    <form id="editEmployeeForm">
        <input type="hidden" name="idempleado" id="editIdEmpleado">
        RUT: <input type="text" name="rut" id="editRut" required><br>
        Nombre: <input type="text" name="nombre" id="editNombre" required><br>
        Apellido: <input type="text" name="apellido" id="editApellido" required><br>
        Rol: 
        <select name="rol_idrol" id="editRol">
            <?php foreach($roles as $rol): ?>
                <option value="<?php echo $rol['idrol']; ?>"><?php echo $rol['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        Cargo: 
        <select name="cargo_idcargo" id="editCargo">
            <?php foreach($cargos as $cargo): ?>
                <option value="<?php echo $cargo['idcargo']; ?>"><?php echo $cargo['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Guardar Cambios">
    </form>
    <button onclick="closeEditEmployeeModal()">Cerrar</button>
</div>


<script>

/**
 * Este código PHP define funciones para abrir y cerrar un modal y maneja el envío del formulario
 * enviando una solicitud POST a un archivo PHP y mostrando la respuesta.
 */
// Funciones para abrir y cerrar el modal
function openAddEmployeeModal() {
    document.getElementById('addEmployeeModal').style.display = 'block';
}

function closeAddEmployeeModal() {
    document.getElementById('addEmployeeModal').style.display = 'none';
}

// Evento de envío del formulario
document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('añadirEmpleado.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeAddEmployeeModal();
        // Aquí puedes agregar la lógica para recargar la tabla o la página
        location.reload(); // Esto recargará toda la página
    })
    .catch(error => {
        alert('Error: ' + error);
    });
});

/**
 * Este código   es una función PHP que se utiliza para editar datos de empleados completando un
 * formulario con la información del empleado, mostrando un modal y enviando los datos del formulario a
 * un script PHP para su procesamiento.
 */
function editarEmpleado(datosEmpleado) {
    // Llena el formulario con los datos del empleado
    document.getElementById('editIdEmpleado').value = datosEmpleado.idempleado;
    // Repite para los demás campos del formulario

    // Muestra el modal
    document.getElementById('editEmployeeModal').style.display = 'block';
}

function closeEditEmployeeModal() {
    document.getElementById('editEmployeeModal').style.display = 'none';
}
document.getElementById('editEmployeeForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('editarEmpleado.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeEditEmployeeModal();
        location.reload();
    })
    .catch(error => {
        alert('Error: ' + error);
    });
});

/**
 * La función `buscarEmpleado` se utiliza para buscar un empleado mediante un término de búsqueda y
 * actualizar una tabla con los resultados.
 */
function buscarEmpleado() {
    var terminoBusqueda = document.getElementById('terminoBusqueda').value;
    fetch('buscarEmpleado.php?terminoBusqueda=' + encodeURIComponent(terminoBusqueda))
    .then(response => response.json())
    .then(data => {
        actualizarTablaEmpleados(data);
    })
    .catch(error => console.error('Error:', error));
}

/**
 * La función "actualizarTablaEmpleados" actualiza el elemento HTML "tablaEmpleados" con nuevos datos
 * construyendo una nueva tabla basada en los datos recibidos.
 */
function actualizarTablaEmpleados(data) {
    var tablaEmpleadosDiv = document.getElementById('tablaEmpleados');
    tablaEmpleadosDiv.innerHTML = ''; // Limpia la tabla existente antes de añadir los nuevos resultados
    // Construye la nueva tabla basada en los datos recibidos
    var html = construirTablaEmpleados(data);
    tablaEmpleadosDiv.innerHTML = html;
}
/**
 * La función construye una tabla HTML basada en los datos de los empleados.
 * 
 * @return una cadena HTML que representa una tabla.
 */

// Construye el HTML de la tabla basado en los datos de los empleados
function construirTablaEmpleados(empleados) {
    var html = '<table border="1"><tr><th>ID Empleado</th><th>RUT</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Cargo</th><th>Acciones</th></tr>';
    empleados.forEach(function(empleado) {
        html += "<tr>" +
                    "<td>" + empleado.idempleado + "</td>" +
                    "<td>" + empleado.rut + "</td>" +
                    "<td>" + empleado.nombre + "</td>" +
                    "<td>" + empleado.apellido + "</td>" +
                    "<td>" + empleado.nombre_rol + "</td>" +
                    "<td>" + empleado.nombre_cargo + "</td>" +
                    "<td>" +
                        "<button class='editar' data-empleado='" + JSON.stringify(empleado) + "'>Editar</button>" +
                        "<button class='eliminar' data-id='" + empleado.idempleado + "'>Eliminar</button>" +
                    "</td>" +
                "</tr>";
    });
    html += '</table>';
    return html;
}

/**
 * Este código está escrito en PHP e incluye una función para restablecer la vista a la lista completa de
 * empleados y un detector de eventos para que la tabla de empleados maneje las acciones de edición y
 * eliminación.
 */
// Restablece la vista a la lista completa de empleados
function restablecerVista() {
    document.getElementById('terminoBusqueda').value = '';
    buscarEmpleado(); 
}



// Este código se ejecuta una sola vez, cuando la página se carga por primera vez
document.addEventListener('DOMContentLoaded', function() {
    // Delegación de evento para la tabla de empleados
    document.getElementById('tablaEmpleados').addEventListener('click', function(e) {
        // Comprueba si el clic fue en un botón con la clase 'editar'
        if (e.target && e.target.classList.contains('editar')) {
            var datosEmpleado = JSON.parse(e.target.getAttribute('data-empleado'));
            editarEmpleado(datosEmpleado);
        }
        // Comprueba si el clic fue en un botón con la clase 'eliminar'
        else if (e.target && e.target.classList.contains('eliminar')) {
            var idEmpleado = e.target.getAttribute('data-id');
            eliminarEmpleado(idEmpleado);
        }
    });
});

/**
 * La función `eliminarEmpleado` se utiliza para eliminar un empleado de una base de datos y recargar
 * la página para reflejar los cambios.
 */
function eliminarEmpleado(idEmpleado) {
    if(confirm('¿Estás seguro de que quieres eliminar a este empleado?')) {
        $.post('eliminarEmpleado.php', {idempleado: idEmpleado}, function(response) {
            alert(response);
            window.location.reload(); // Recargar la página para ver los cambios
        });

        fetch('eliminarEmpleado.php', {
            method: 'POST',
            body: new URLSearchParams('idempleado=' + idEmpleado)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.reload(); // Recargar la página para ver los cambios
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

<?php 
?>
</body>
</html>