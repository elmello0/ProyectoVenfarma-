<!-- Este documento HTML define la estructura básica de una página web con enfoque en la administración de productos.
     - Incluye la declaración DOCTYPE para HTML5 y etiquetas <html> y <head> con configuraciones estándar de codificación y visualización responsiva.
     - Un enlace a la hoja de estilos 'estilos_menu_admin.css' para el diseño.
     - Un script en el cuerpo que, al cargar el documento, ejecuta 'buscarProducto()' para cargar datos inicialmente.
     - Un encabezado con el título 'Productos' y un subencabezado que contiene botones de navegación para diferentes secciones como 'Menú Admin', 'Productos', 'Demandas', 'Historial' y 'Empleados', cada uno enlazado a su respectivo archivo PHP.
     - Un 'div' con un 'h1' vacío, posiblemente para ser llenado dinámicamente o para futuras expansiones del contenido. -->
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estilos_menu_admin.css">
    
</head>
<body>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            buscarProducto(); // Llama a esta función para cargar la tabla inicialmente
        });
    </script>
<header>
    Productos
    <header>
        <a href="menu_admin.php"><button>Menú Admin</button></a>
        <a href="getProductos.php"><button>Productos</button></a>
        <a href="getDemandas.php"><button>Demandas</button></a>
        <a href="getHistorial.php"><button>Historial</button></a>
        <a href="getEmpleados.php"><button>Empleados</button></a>
    </header>
</header>
<div>
    <h1> </h1>
</div>
<!-- Este fragmento de código incluye un formulario de búsqueda, botones para diversas acciones y un script para cargar promociones.
     - El 'form' envía una petición GET a 'buscar_producto.php' con un campo de entrada para buscar productos.
     - Botones en 'div.container' para añadir productos, categorías, familias, tipos y para alternar la visualización de productos activos/inactivos.
     - Un 'div#tablaProductos' probablemente utilizado para mostrar resultados.
     - Un script que, al cargar el documento, llama a 'loadAllPromotions()' para obtener promociones desde 'get_promotions.php', mostrando los datos en consola y manejando errores. -->
<form action="buscar_producto.php" method="GET">
    <label for="nombre_producto">Buscar Producto:</label>
    <input type="text" id="terminoBusqueda" onkeyup="buscarProducto()" placeholder="Buscar producto...">
    
</form>

<div class="container">
<button onclick="openModal()">Añadir Producto</button>
<button id="toggleActiveProducts" onclick="toggleActiveProducts()">Ocultar Inactivos</button>
<button onclick="openCategoryModal()">Añadir Categoría</button>
<button onclick="openFamilyModal()">Añadir Familia</button>
<button onclick="openTypeModal()">Añadir Tipo</button>
</div>
<div id="tablaProductos"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAllPromotions();
});

function loadAllPromotions() {
    fetch('get_promotions.php')
        .then(response => response.json())
        .then(data => {
            allPromotions = data;
            console.log(allPromotions); // Verifica los datos en la consola
        })
        .catch(error => console.error('Error al cargar promociones:', error));
}

</script>
</script>
<!-- obtener los datos de cada opcion en el menu desplegable --------------------------------------------------------> 
<!-- Este script JavaScript es para rellenar un selector de categorías en un formulario de manera dinámica.
     - Hace una petición fetch a 'get_categories.php' para obtener datos de categorías.
     - Transforma la respuesta a formato JSON.
     - Encuentra el elemento 'select' con el nombre 'categoria_idcategoria' en el documento.
     - Limpia cualquier opción existente en este selector para añadir nuevas.
     - Itera sobre cada objeto 'category' en el JSON y por cada uno:
       - Crea un elemento 'option'.
       - Asigna el valor de 'option' al 'id' de 'category'.
       - Establece el texto del 'option' con el 'nombre' de 'category'.
       - Añade el 'option' al selector 'categoria_idcategoria'.
     - Incluye manejo de errores para capturar y registrar cualquier error en la consola. -->
    <script>
        // AJAX para obtener las categorías y cargarlas en el menú desplegable
        fetch('get_categories.php')
            .then(response => response.json())
            .then(categories => {
                // Obtener el select del formulario
                const selectCategory = document.querySelector('select[name="categoria_idcategoria"]');

                // Limpiar opciones existentes
                selectCategory.innerHTML = '';

                // Agregar las nuevas opciones desde la respuesta JSON
                categories.forEach(category => {
    const option = document.createElement('option');
    option.value = category.id; // Usar 'id' en lugar de 'idcategoria'
    option.textContent = category.nombre;
    selectCategory.appendChild(option);
});
            })
            .catch(error => console.error('Error:', error));
    </script>
    <!-- Este script JavaScript está diseñado para cargar dinámicamente un selector de familias en un formulario.
     - Utiliza una solicitud fetch a 'get_families.php' para obtener datos de familias.
     - Al recibir la respuesta, la convierte a formato JSON.
     - Selecciona el elemento 'select' con nombre 'familia_idfamilia' del documento.
     - Limpia las opciones actuales del selector para añadir nuevas.
     - Itera sobre cada objeto 'family' en la respuesta JSON y por cada uno:
       - Crea un nuevo elemento 'option'.
       - Asigna el valor de 'option' al 'id' de 'family'.
       - Establece el texto de 'option' con el 'nombre' de 'family'.
       - Agrega el 'option' creado al selector 'familia_idfamilia'.
     - Implementa manejo de errores para registrar cualquier error en la consola. -->
    <script>
    fetch('get_families.php')
        .then(response => response.json())
        .then(families => {
            // Obtener el select del formulario
            const selectFamily = document.querySelector('select[name="familia_idfamilia"]');

            // Limpiar opciones existentes
            selectFamily.innerHTML = '';

            // Agregar las nuevas opciones desde la respuesta JSON
            families.forEach(family => {
    const option = document.createElement('option');
    option.value = family.id; // Usar 'id' en lugar de 'idfamilia'
    option.textContent = family.nombre;
    selectFamily.appendChild(option);
});
        })
        .catch(error => console.error('Error:', error));
</script>
<!-- Este script JavaScript se utiliza para poblar dinámicamente un selector de tipos en un formulario HTML.
     - Realiza una solicitud fetch a 'get_types.php' para obtener datos.
     - Al recibir una respuesta, la procesa como JSON.
     - Accede al elemento select con nombre 'tipo_idtipo' en el documento.
     - Limpia cualquier opción existente en este selector para prepararlo para nuevas entradas.
     - Itera sobre cada 'type' en la respuesta JSON y para cada uno:
       - Crea un elemento 'option'.
       - Establece el valor de la opción con el 'id' del 'type'.
       - Establece el texto de la opción con el 'nombre' del 'type'.
       - Agrega la opción creada al selector 'tipo_idtipo'.
     - Incluye manejo de errores para lograr cualquier error en la consola. -->
<script>
    
    fetch('get_types.php')
        .then(response => response.json())
        .then(types => {
            // Obtener el select del formulario
            const selectType = document.querySelector('select[name="tipo_idtipo"]');

            // Limpiar opciones existentes 
            selectType.innerHTML = '';

            // Agregar las nuevas opciones desde la respuesta JSON
            types.forEach(type => {
    const option = document.createElement('option');
    option.value = type.id; // Usar 'id' en lugar de 'idtipo'
    option.textContent = type.nombre;
    selectType.appendChild(option);
});

        })
        .catch(error => console.error('Error:', error));
</script>



</div>
<!-- Modal para editar producto -->
<!-- Este fragmento de código HTML es para una ventana modal destinada a la edición de productos.
     - 'div#editProductModal': Contenedor modal con estilos para centrarlo en la pantalla y mantenerlo oculto inicialmente.
     - 'h2': Título indicando que la ventana modal es para editar productos.
     - 'form#editProductForm': Formulario que incluye:
       - Campos para editar el nombre, stock y precio del producto, todos requeridos.
       - Selector de estado con opciones 'activo' e 'inactivo'.
       - Botón para abrir una modal de promociones en modo edición y un campo oculto para guardar el ID de la promoción seleccionada.
       - Área para mostrar el nombre de la promoción actualmente seleccionada.
       - Selectores para categoría, familia y tipo del producto, todos requeridos.
       - Campo oculto para almacenar el ID del producto que se está editando.
       - Botón de envío para guardar los cambios realizados.
     - Botón 'Cerrar' que activa 'closeEditModal()' para ocultar la ventana modal. -->
<div id="editProductModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Editar Producto</h2>
    <form id="editProductForm">
        <!-- Otros campos del formulario -->
        Nombre Producto: <input type="text" name="nombre" required><br>
        Stock: <input type="number" name="stock" required><br>
        Precio Producto: <input type="number" name="precio" step="0.01" required><br>
        Estado:<select name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select><br>
        <button type="button" onclick="openPromotionModalForEdit()">Seleccionar Promoción</button><br>
        <input type="hidden" id="selectedPromotionIdEdit" name="promocion_idpromocion">
        Promoción Seleccionada:<span id="promotionNameDisplayEdit">Ninguna</span><br><br>
        Categoría:<select name="categoria_idcategoria" required></select><br>
        Familia:<select name="familia_idfamilia" required></select><br>
        Tipo:<select name="tipo_idtipo" required></select><br>
        <input type="hidden" name="idProducto">
        <input type="submit" value="Guardar Cambios">
    </form>
    <button onclick="closeEditModal()">Cerrar</button>
</div>


<!-- Modal para añadir producto -->
<!-- Este fragmento de código HTML implementa una ventana modal para añadir productos.
     - 'div#addProductModal': Contenedor modal con estilo para centrarlo y mantenerlo inicialmente oculto.
     - 'h2': Encabezado que indica que la modal es para añadir productos.
     - 'form#addProductForm': Formulario que contiene:
       - Campo para el nombre del producto, campo numérico para stock y precio, todos requeridos.
       - Selector de estado con opciones 'activo' e 'inactivo'.
       - Botón para abrir una modal de promociones y un campo oculto para almacenar el ID de la promoción seleccionada.
       - Visualización del nombre de la promoción seleccionada.
       - Selectores para categoría, familia y tipo del producto, todos requeridos.
       - Botón de envío para añadir el producto.
     - Botón 'Cerrar' para ocultar la ventana modal, vinculado a 'closeModal()'. -->

<div id="addProductModal" style="display:none; position:fixed; top:35%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Producto</h2>
    <form id="addProductForm">
        <!-- Campos del formulario para añadir producto -->
        Nombre Producto: <input type="text" name="nombre" required><br>
        Stock: <input type="number" name="stock" required><br>
        Precio Producto: <input type="number" name="precio" step="0.01" required><br>
        Estado:<select name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select><br>
        <button type="button" onclick="openPromotionModal()">Seleccionar Promoción</button><br>
        <input type="hidden" id="selectedPromotionId" name="promocion_idpromocion">
        Promoción Seleccionada:<span id="promotionNameDisplay">Ninguna</span><br><br>
        Categoría:<select name="categoria_idcategoria" required></select><br>
        Familia:<select name="familia_idfamilia" required></select><br>
        Tipo:<select name="tipo_idtipo" required></select><br>
        <input type="submit" value="Añadir Producto">
    </form>
    <button onclick="closeModal()">Cerrar</button>
</div>



<!-- Modal para añadir categoría ---------------------------------------------------->
<!-- Este código HTML representa una ventana modal para añadir categorías.
     - 'div#addCategoryModal': Contenedor modal, inicialmente oculto, estilizado y centrado.
     - 'h2': Título de la funcionalidad.
     - 'form#addCategoryForm': Formulario con campos para el nombre y descripción de la categoría y un botón de envío.
     - 'button': Botón para cerrar la ventana, vinculado a 'closeCategoryModal()'. -->

<div id="addCategoryModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Categoría</h2>
    <form id="addCategoryForm">
        Nombre Categoría: <input type="text" name="nombre_categoria" required><br>
        Descripción: <input type="text" name="descripcion_categoria"><br>
        <input type="submit" value="Añadir Categoría">
    </form>
    <button onclick="closeCategoryModal()">Cerrar</button>
</div>



<!-- Este código siguiente representa un modal HTML utilizado para añadir familias. 
Este modal incluye un campo para ingresar el nombre de una nueva familia y un botón para añadirla. 
También contiene un botón para cerrar el modal. El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->
<!-- Modal para añadir familia -------------------------------------------------------->


<div id="addFamilyModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Familia</h2>
    <form id="addFamilyForm">
        Nombre: <input type="text" name="nombre_familia" required><br>
        <input type="submit" value="Añadir Familia">
    </form>
    <button onclick="closeFamilyModal()">Cerrar</button>
</div>


<!-- Este código   representa un modal HTML utilizado para añadir tipos. 
Este modal incluye un campo para ingresar el nombre de un nuevo tipo y un botón para añadirlo.
 También contiene un botón para cerrar el modal. El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->
<!-- Modal para añadir tipo --------------------------------------------------------------->


<div id="addTypeModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Tipo</h2>
    <form id="addTypeForm">
        Nombre: <input type="text" name="nombre_tipo" required><br>
        <input type="submit" value="Añadir Tipo">
    </form>
    <button onclick="closeTypeModal()">Cerrar</button>
</div>

<!-- Este código   representa un modal HTML utilizado para añadir demandas. Este modal incluye 
campos para ingresar el nombre, fecha, descripción y estado de una nueva demanda. También contiene un 
botón para añadir la demanda y otro para cerrar el modal. El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->

<!-- Modal para añadir demanda -->
<div id="addDemandModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Demanda</h2>
    <form id="addDemandForm">
        Nombre: <input type="text" name="nombre_demanda" required><br>
        Fecha: <input type="datetime-local" name="fecha_demanda" required><br>
        Descripción: <textarea name="descripcion_demanda" required></textarea><br>
        Estado: 
        <select name="estado_demanda" required>
            <option value="SI">SI</option>
            <option value="NO">NO</option>
        </select><br>
        <input type="submit" value="Añadir Demanda">
    </form>
    <button onclick="closeDemandModal()">Cerrar</button>
</div>


<!-- Este código   representa un modal HTML utilizado para seleccionar promociones. 
El modal incluye una tabla que muestra información sobre las promociones, como ID, nombre,
 estado, descuento, fecha de inicio y fecha final. Los datos de cada promoción se cargarían dinámicamente en la tabla. 
 El modal también contiene botones para cerrar el modal, ocultar/promociones inactivas y añadir una nueva promoción. 
 El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->

<div id="promotionModal" style="display:none; position:fixed; top:45%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 0;">
    <h2>Seleccionar Promoción</h2>
    <div id="tableContainer" style="max-height: 400px; overflow-y: auto;">
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Descuento</th>
            <th>Fecha Inicio</th>
            <th>Fecha Final</th>
            <th>Acciones</th>
        </tr>
        <!-- Los datos de cada promoción se cargarían aquí dinámicamente -->
    </table>
    <!-- Boton para cerrar el modal -->
    </div>
    <button onclick="closePromotionModal()">Cerrar</button>
    <button id="toggleActivePromotions" onclick="toggleActivePromotions()">Ocultar Inactivos</button>
    <button onclick="openAddPromotionModal()">Añadir Promoción</button> 
    
</div>



<!-- Este código   representa un modal HTML utilizado para añadir promociones.
 Este modal incluye campos para ingresar el nombre, estado, descuento y fechas de inicio y 
 finalización de una nueva promoción. También contiene un botón para crear la promoción y otro 
 para cerrar el modal. El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->

<!-- Modal para añadir promoción -------------------------------------------------->

<div id="addPromotionModal" style="display:none; position:fixed; top:30%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px;">
    <h2>Añadir Promoción</h2>
    <form id="addPromotionForm">
        Nombre: <input type="text" name="nombre_promocion" required><br>
        Estado: <select name="estado_promocion" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select><br>
        Descuento: <input type="number" name="descuento_promocion" min="0" max="100" required><br>
        Fecha Inicio: <input type="date" name="fecha_inicio_promocion" required><br>
        Fecha Final: <input type="date" name="fecha_final_promocion" required><br>
        <input type="submit" value="Crear Promoción">
    </form>
    <button onclick="closeAddPromotionModal()">Cerrar</button>
</div>



<!-- Este código   representa un modal HTML utilizado para editar promociones. 
Este modal incluye campos para modificar el nombre, estado, descuento y
 fechas de inicio y finalización de una promoción. También contiene botones para
  guardar los cambios y cancelar la edición. El modal está oculto por defecto (display:none) y se muestra cuando es necesario. -->

<!-- Modal de Edición de Promoción -->
<div id="editPromotionModal" style="display:none; position:fixed; top:30%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 1  ;">
    <h2>Editar Promoción</h2>
    <form id="editPromotionForm">
        <input type="hidden" id="editIdPromocion" name="idpromocion">
        <label for="editNombre">Nombre:</label>
        <input type="text" id="editNombre" name="nombre"><br>
        <label for="editEstado">Estado:</label>
        <select id="editEstado" name="estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select><br>
        <label for="editDescuento">Descuento:</label>
        <input type="number" id="editDescuento" name="descuento"><br>
        <label for="editFechaInicio">Fecha Inicio:</label>
        <input type="date" id="editFechaInicio" name="fecha_inicio"><br>
        <label for="editFechaFinal">Fecha Final:</label>
        <input type="date" id="editFechaFinal" name="fecha_final"><br>
        <button type="button" onclick="submitEditPromotion()">Guardar Cambios</button>
        <button type="button" onclick="closeEditPromotionModal()">Cancelar</button>
    </form>
</div>





<!----------------------------------------------------------------Editar-------------------------------------------------------->
<!-- Este código   contiene varias funciones JavaScript relacionadas con la edición de productos y la 
gestión de modales. La función 'selectPromotionForEdit' se utiliza para seleccionar una promoción en modo de edición.
 'openEditModal' se encarga de mostrar el modal de edición de productos y cargar los datos del producto seleccionado. 
 'closeEditModal' cierra el modal de edición. 'openPromotionModalForEdit' se utiliza para abrir el modal de promociones 
 en modo de edición y realizar la solicitud para cargar las promociones desde la base de datos. -->

<script>
function selectPromotionForEdit() {
    openPromotionModalForEdit();
    window.isEditMode = true; // Establece el modo a "editar"
}

    
    function openEditModal(idProducto) {
    document.getElementById("editProductModal").style.display = "block";
    loadProductData(idProducto); // Mover esta línea después de mostrar el modal
}


function closeEditModal() {
    document.getElementById('editProductModal').style.display = "none";
}
function openPromotionModalForEdit() {
    fetchPromotionsFromDatabase().then(buildTableForEdit).catch(handleError);
    document.getElementById('promotionModalEdit').style.display = 'block';
}


/* Este código   contiene una función JavaScript llamada 'submitEditProductForm', 
que se encarga de enviar un formulario de edición de producto mediante una solicitud fetch. 
Después de procesar la respuesta JSON, la función actualiza la interfaz de usuario y muestra 
mensajes de éxito o error. Asegúrate de adjuntar esta función al evento de envío del formulario 
para manejar la solicitud de manera asíncrona y evitar la recarga de la página al enviar el formulario.*/

function submitEditProductForm() {
    var form = document.getElementById('editProductForm');
    var formData = new FormData(form);

    fetch('update_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            buscarProducto();
            closeEditModal();
            alert('Producto actualizado con éxito'); // Mensaje de confirmación
        } else {
            alert('No se pudo actualizar el producto: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al actualizar el producto.');
    });
}

document.getElementById('editProductForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    submitEditProductForm();
});

/* Este código   contiene funciones JavaScript que se utilizan para seleccionar una promoción y 
actualizar la visualización del nombre de la promoción en formularios modales, tanto en el modo de edición 
como en el modo de añadir producto. También cierra el modal de promociones si está abierto.
 La función 'updatePromotionNameDisplayEdit' se utiliza para actualizar el nombre de la promoción en el formulario modal de edición.*/

function selectPromotion(id, isEditMode = false) {
    var promotion = allPromotions.find(promo => promo.idpromocion == id);
    var promotionName = promotion ? promotion.nombre : 'No se encontró la promoción';

    if (isEditMode) {
        // Actualizar para el modal de Editar Producto
        var selectedPromotionInputEdit = document.getElementById('selectedPromotionIdEdit');
        var promotionNameDisplayEdit = document.getElementById('promotionNameDisplayEdit');
        if (selectedPromotionInputEdit && promotionNameDisplayEdit) {
            selectedPromotionInputEdit.value = id;
            promotionNameDisplayEdit.textContent = promotionName;
        }
    } else {
        // Actualizar para el modal de Añadir Producto
        var selectedPromotionInputAdd = document.getElementById('selectedPromotionId');
        var promotionNameDisplayAdd = document.getElementById('promotionNameDisplay');
        if (selectedPromotionInputAdd && promotionNameDisplayAdd) {
            selectedPromotionInputAdd.value = id;
            promotionNameDisplayAdd.textContent = promotionName;
        }
    }

    // Cierra el modal de promociones si está abierto
    closePromotionModal();
}



// Llamar a esta función al cargar la página o cuando sea necesario
loadAllPromotions();

function updatePromotionNameDisplayEdit(promocionId) {
    var promotion = allPromotions.find(promo => promo.idpromocion == promocionId);
    var promotionNameDisplayEdit = document.getElementById('promotionNameDisplayEdit');
    if (promotionNameDisplayEdit) {
        promotionNameDisplayEdit.textContent = promotion ? promotion.nombre : 'Promoción no encontrada';
    }
}

/* Este código   es una función JavaScript que realiza una solicitud fetch para cargar datos de un producto.
    Utiliza la respuesta JSON para llenar campos en un formulario modal de edición y maneja errores de manera adecuada. */

function loadProductData(productId) {
    fetch('editar_producto.php?id=' + productId)
        .then(response => response.json())
        .then(productData => {
            document.querySelector('#editProductModal input[name="nombre"]').value = productData.nombre;
            document.querySelector('#editProductModal input[name="stock"]').value = productData.stock;
            document.querySelector('#editProductModal input[name="precio"]').value = productData.precio;
            document.querySelector('#editProductModal select[name="estado"]').value = productData.estado;
            document.querySelector('#editProductModal input[name="idProducto"]').value = productId;

            // Actualizar la promoción seleccionada
            updatePromotionNameDisplayEdit(productData.promocion_idpromocion);
        })
        .catch(error => {
            console.error('Error al cargar los datos del producto:', error);
            alert('Error al cargar los datos del producto.');
        });
}
</script>


<script>
//----------------------------------------------------------------Producto-----------------------------------
/**
*La función `loadSelectOptions` obtiene datos de una URL especificada, completa un elemento seleccionado con
*los datos recuperados y borra cualquier opción existente.
*/
function loadSelectOptions(url, selectElementName, modalId) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const modalElement = document.getElementById(modalId);
            const selectElement = modalElement.querySelector(`select[name="${selectElementName}"]`);
            selectElement.innerHTML = ''; // Limpiar opciones existentes
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nombre;
                selectElement.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}



/**
*La función "selectPromotionForAdd" establece el modo en "agregar" y abre un modal para seleccionar un
*promoción.
*/
function selectPromotionForAdd() {
    openPromotionModal();
    window.isEditMode = false; 
}

/**
*La función openModal muestra un modal y carga opciones de selección de diferentes fuentes.
*/
function openModal() {
    document.getElementById("addProductModal").style.display = "block";
    // Cargar opciones para los selects
    loadSelectOptions('get_categories.php', 'categoria_idcategoria', 'addProductModal');
    loadSelectOptions('get_families.php', 'familia_idfamilia', 'addProductModal');
    loadSelectOptions('get_types.php', 'tipo_idtipo', 'addProductModal');
}

/**
*La función closeModal oculta el elemento addProductModal.
*/
function closeModal() {
    document.getElementById('addProductModal').style.display = "none";
}

/**
*La función "openPromotionModal" recupera promociones de una base de datos, crea una tabla con los
*datos recuperados y muestra un modal.
*/
function openPromotionModal() {
    fetchPromotionsFromDatabase().then(buildTable).catch(handleError);
    document.getElementById('promotionModal').style.display = 'block';
}

/**
 *La función `submitAddProductForm` envía datos de un formulario al servidor usando el método POST y
 *muestra un mensaje de éxito si el producto se agregó exitosamente, o un mensaje de error si hay un
 *asunto.
 */
function submitAddProductForm() {
    var form = document.getElementById('addProductForm');
    var formData = new FormData(form);

    fetch('insert_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            buscarProducto();
            closeModal();
            alert('Producto añadido con éxito'); // Mensaje de confirmación
        } else {
            alert('No se pudo añadir el producto: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al añadir el producto.');
    });
}


/*Este código   está escrito en JavaScript, no en PHP. Está agregando un detector de eventos al formulario con
la identificación "addProductForm". Cuando se envía el formulario, el detector de eventos evitará que el formulario
siendo presentado de la forma tradicional. En su lugar, llamará a la función "submitAddProductForm()".
para manejar el envío del formulario.*/
document.getElementById('addProductForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Esto evitará que el formulario se envíe de la manera tradicional
    submitAddProductForm();
});

/**
*La función "selectPromotion" encuentra una promoción por su ID, actualiza la entrada de promoción seleccionada y
*muestra el nombre de la promoción.
*/
function selectPromotion(id) {
    var promotion = allPromotions.find(promo => promo.idpromocion == id);
    var promotionName = promotion ? promotion.nombre : 'No se encontró la promoción';
    var selectedPromotionInput = document.getElementById('selectedPromotionId');
    var promotionNameDisplay = document.getElementById('promotionNameDisplay');
    if (selectedPromotionInput && promotionNameDisplay) {
        selectedPromotionInput.value = id;
        promotionNameDisplay.textContent = promotionName;
    }
    closePromotionModal();
}

// Llamar a esta función al cargar la página o cuando sea necesario
/*Este código   llama a una función llamada "loadAllPromotions" en PHP. */
loadAllPromotions();
</script>


<script>
//--------------------------------------------------Promoción -------------------------------------------------------------
//BOTONES ----------------------------------------------------------------------------------------------------------------
/**
*La función "openAddPromotionModal" se utiliza para mostrar el elemento "addPromotionModal".
*/
    function openAddPromotionModal() {
    document.getElementById('addPromotionModal').style.display = 'block';
}

/**
*La función closeAddPromotionModal() oculta el elemento addPromotionModal.
*/
function closeAddPromotionModal() {
    document.getElementById('addPromotionModal').style.display = 'none';
}



/*Este código   está escrito en JavaScript y agrega un detector de eventos a un formulario con la identificación
"agregar formulario de promoción". Cuando se envía el formulario, evita el comportamiento de envío de formulario predeterminado. Él
luego crea un nuevo objeto FormData a partir de los datos del formulario. */
document.getElementById('addPromotionForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);

    fetch('insert_promotion.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Promoción añadida con éxito');
            reloadPromotionsAndUpdateTable(); // Actualizar la tabla
        } else {
            alert('Error al añadir promoción');
        }
    })
    .catch(error => {
        alert('Error al añadir promoción: ' + error);
    });
});

/**
 *La función recarga datos de promociones desde un script PHP y actualiza una tabla con los datos recuperados.
 */
function reloadPromotionsAndUpdateTable() {
    fetch('get_promotions.php')
        .then(response => response.json())
        .then(data => {
            buildTable(data); 
        })
        .catch(error => console.error('Error:', error));
}

/**
 *La función "openPromotionModal" recupera promociones de una base de datos, crea una tabla con los
 *recupera datos y maneja cualquier error que ocurra.
 */
function openPromotionModal() {
    fetchPromotionsFromDatabase().then(buildTable).catch(handleError);
}

/**
 *La función closePromotionModal oculta el elemento promociónModal.
 */
function closePromotionModal() {
    document.getElementById('promotionModal').style.display = 'none';
}

/**
 *La función closeEditPromotionModal() oculta el modal editPromotionModal.
 */
function closeEditPromotionModal() {
    // Ocultar el modal de edición
    document.getElementById('editPromotionModal').style.display = 'none';
}
//Botón para alternar la visualización de promociones activas/inactivas
/*Este código es una función PHP que alterna la visibilidad de promociones inactivas en una tabla. */
function toggleActivePromotions() {
    // Obtener todas las filas de la tabla
    var table = document.getElementById('promotionModal').getElementsByTagName('table')[0];
    var rows = table.getElementsByTagName('tbody')[0].rows;
    
    // Cambiar el texto del botón basado en el estado actual
    var toggleButton = document.getElementById('toggleActivePromotions');
    toggleButton.innerText = showInactive ? 'Mostrar Inactivos' : 'Ocultar Inactivos';
    
    // Recorrer las filas y ocultar/mostrar las inactivas
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var statusCell = row.cells[2]; 
        
        // Si el estado es 'inactivo', ocultar/mostrar la fila
        if (statusCell.innerText.toLowerCase() === 'inactivo') {
            row.style.display = showInactive ? 'none' : ''; // Alterna entre ocultar y mostrar
        }
    }
    
    // Alternar el estado de la variable
    showInactive = !showInactive;
}
// BOTONES -------------------------------- ------------------------------------------------ -------------------------------- 

    // Variables globales

/*Este código   declara dos variables, "showInactive" y "selectedPromotionIdAdd", y
inicializándolos con los valores "verdadero" y "nulo" respectivamente. También declara otra
variable "selectedPromotionIdEdit" e inicializándola con el valor "null". */
var showInactive = true;

var selectedPromotionIdAdd = null;
var selectedPromotionIdEdit = null;


/**
 *La función "getPromotionDataById" recupera datos de promoción por su ID de una matriz de todos
 *promociones.
 *
 *@return los datos de la promoción que coinciden con la identificación proporcionada.
 */
function getPromotionDataById(id) {
    // Encuentra la promoción por su id en el array de todas las promociones
    return allPromotions.find(promotion => promotion.idpromocion == id);
}



/**
 *La función "editarPromoción" recupera datos de promoción por ID y llena un formulario con los datos.
 *
 *@no devolver nada.
 */
function editPromotion(id) {
    var promotionData = getPromotionDataById(id);
    if (!promotionData) {
        console.error('Promoción no encontrada con ID:', id);
        return;
    }
    // Rellena el formulario con los datos de la promoción
    document.getElementById('editIdPromocion').value = promotionData.idpromocion;
    document.getElementById('editNombre').value = promotionData.nombre;
    document.getElementById('editEstado').value = promotionData.estado;
    document.getElementById('editDescuento').value = promotionData.descuento;
    document.getElementById('editFechaInicio').value = promotionData.fecha_inicio;
    document.getElementById('editFechaFinal').value = promotionData.fecha_final;
    document.getElementById('editPromotionModal').style.display = 'block';
}

/**
 *La función recargarListaProductos recupera datos de listarProductos.php, los convierte a JSON
 *formato, y luego llama a la función actualizarTablaProductos con los datos recuperados.
 */
function recargarListaProductos() {
    fetch('listarProductos.php') 
    .then(response => response.json())
    .then(data => {
        actualizarTablaProductos(data);
    })
    .catch(error => console.error('Error:', error));
}


/**
 *La función `submitEditPromotion` envía los datos del formulario de promoción editado al servidor, cierra el
 *modal, y recarga la tabla de promociones si la respuesta es exitosa.
 */
function submitEditPromotion() {
    var formData = new FormData(document.getElementById('editPromotionForm'));

    updatePromotionOnServer(formData).then(response => {
        if(response.success) {
            // Si la respuesta es exitosa, cierra el modal y actualiza la tabla
            closeEditPromotionModal();
            // Llama a la función para recargar y actualizar la tabla
            reloadPromotions();
        } else {
            // Manejar la respuesta fallida, como mostrar un mensaje de error
            console.error('Error al guardar la edición:', response.error);
        }
    }).catch(error => {
        console.error('Error al actualizar la promoción:', error);
    });
}

/**
 *La función reloadPromotions recupera promociones de una base de datos, crea una tabla con las promociones obtenidas
 *datos y maneja cualquier error que ocurra.
 */
function reloadPromotions() {
    fetchPromotionsFromDatabase().then(buildTable).catch(handleError);
}

/**
 *La función `updatePromotionOnServer` envía una solicitud POST al servidor con datos del formulario y devuelve
 *una promesa que se resuelve en la respuesta JSON.
 *
 *@return una Promesa que se resuelve en la respuesta JSON del servidor.
 */
function updatePromotionOnServer(formData) {
    var updateUrl = 'update_promotion.php';

    return fetch(updateUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}




/**
 *La función "buildTable" crea una tabla en HTML y la llena con datos de una matriz de
 *promociones.
 */
function buildTable(promotions) {
    var promotionModal = document.getElementById('promotionModal');
    var table = promotionModal.getElementsByTagName('table')[0];
    table.innerHTML = '';

    var thead = document.createElement('thead');
    var headerRow = thead.insertRow();
    var headers = ['ID', 'Nombre', 'Estado', 'Descuento', 'Fecha Inicio', 'Fecha Final', 'Acciones'];
    headers.forEach(headerText => {
        var th = document.createElement('th');
        th.innerText = headerText;
        headerRow.appendChild(th);
    });
    table.appendChild(thead);

    var tbody = document.createElement('tbody');
    promotions.forEach(promotion => {
        var tr = tbody.insertRow();
        tr.insertCell().innerText = promotion.idpromocion; // ID de la promoción
        tr.insertCell().innerText = promotion.nombre;
        tr.insertCell().innerText = promotion.estado;
        tr.insertCell().innerText = promotion.descuento + '%'; // Descuento con símbolo %
        tr.insertCell().innerText = promotion.fecha_inicio;
        tr.insertCell().innerText = promotion.fecha_final;

        // La columna 'Acciones' es donde se colocarán los botones
        var actionCell = tr.insertCell();
        var selectButton = createButton('Seleccionar', () => handleSelectPromotion(promotion.idpromocion));
        var editButton = createButton('Editar', () => editPromotion(promotion.idpromocion));
        actionCell.appendChild(selectButton);
        actionCell.appendChild(editButton);
    });
    table.appendChild(tbody);
    promotionModal.style.display = 'block';
}



/**
 *La función createButton crea un elemento de botón con el texto especificado y el evento onClick
 *manipulador.
 *
 *@return un elemento de botón con el texto especificado y el controlador de eventos onclick.
 */
function createButton(text, onClick) {
    var button = document.createElement('button');
    button.innerText = text;
    button.onclick = onClick;
    return button;
}

/**
 *La función "handleSelectPromotion" se utiliza para gestionar la selección de una promoción y actualizar la
 *elementos correspondientes en la página.
 */
function handleSelectPromotion(id) {
    var promotion = allPromotions.find(promo => promo.idpromocion == id);
    var promotionName = promotion ? promotion.nombre : 'No se encontró la promoción';

    if (isEditMode) {
        document.getElementById('selectedPromotionIdEdit').value = id;
        document.getElementById('promotionNameDisplayEdit').textContent = promotionName;
    } else {
        document.getElementById('selectedPromotionId').value = id;
        document.getElementById('promotionNameDisplay').textContent = promotionName;
    }

    closePromotionModal();
}




/**
 *La función createButton crea un elemento de botón con el texto especificado y el evento onClick
 *manipulador.
 *
 *@return un elemento de botón con el texto especificado y el controlador de eventos onclick.
 */
function createButton(text, onClick) {
    var button = document.createElement('button');
    button.innerText = text;
    button.onclick = onClick;
    return button;
}
/**
 *La función handleError registra un mensaje de error en la consola.
 */
function handleError(error) {
    console.error('Error: ', error);
}
/**
 *La función fetchPromotionsFromDatabase recupera datos de promociones de una base de datos mediante una solicitud GET
 *y devuelve una promesa que se resuelve con los datos obtenidos.
 *
 *@return La función fetchPromotionsFromDatabase() devuelve una promesa que se resuelve en los datos.
 *obtenido del punto final 'get_promotions.php'.
 */
function fetchPromotionsFromDatabase() {
    return fetch('get_promotions.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            allPromotions = data; // Guardar los datos en la variable allPromotions
            return data; // Continuar la cadena de promesas con los datos de promociones
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}
// ************************************************* PROMOCION CONTEXTO EDITAR********************************************************


/*Este código   declara dos variables en PHP. */

var showInactiveForEdit = true;
var selectedPromotionIdEdit = null;



// Seleccionar promoción (en modo de edición)
/**
 *La función "selectPromotionForEdit" actualiza la visualización y los campos de entrada ocultos en el producto.
 *edita el formulario con el nombre y ID de la promoción seleccionada, y cierra el modal de promoción.
 */
function selectPromotionForEdit(id) {
    var promotion = allPromotions.find(promo => promo.idpromocion == id);
    var promotionName = promotion ? promotion.nombre : 'No se encontró la promoción';

    // Actualizar el span y el input oculto en el formulario de edición del producto
    var promotionNameDisplayEdit = document.getElementById('promotionNameDisplayEdit');
    var selectedPromotionInputEdit = document.getElementById('selectedPromotionIdEdit');
    
    if (promotionNameDisplayEdit && selectedPromotionInputEdit) {
        promotionNameDisplayEdit.textContent = promotionName;
        selectedPromotionInputEdit.value = id;
    }

    // Cierra el modal de promociones
    closePromotionModal();
}




// Editar promoción (en modo de edición)
/**
 *La función "editPromotionForEdit" recupera datos de promoción por ID y llena un formulario con los datos.
 *
 *@no devolver nada.
 */
function editPromotionForEdit(id) {
    var promotionData = getPromotionDataByIdForEdit(id);
    if (!promotionData) {
        console.error('Promoción no encontrada con ID:', id);
        return;
    }

    // Rellena el formulario con los datos de la promoción
    document.getElementById('editIdPromocionForEdit').value = promotionData.idpromocion;
    document.getElementById('editNombreForEdit').value = promotionData.nombre;
    document.getElementById('editEstadoForEdit').value = promotionData.estado;
    document.getElementById('editDescuentoForEdit').value = promotionData.descuento;
    document.getElementById('editFechaInicioForEdit').value = promotionData.fecha_inicio;
    document.getElementById('editFechaFinalForEdit').value = promotionData.fecha_final;
    document.getElementById('editPromotionModalForEdit').style.display = 'block';
}



// Recargar lista de productos (en modo de edición)
/**
 *La función recargarListaProductosForEdit recupera datos de un archivo PHP y actualiza una tabla con el
 *datos recibidos.
 */
function recargarListaProductosForEdit() {
    fetch('listarProductos.php') 
    .then(response => response.json())
    .then(data => {
        actualizarTablaProductosForEdit(data);
    })
    .catch(error => console.error('Error:', error));
}

// Enviar edición de promoción al servidor (en modo de edición)
/**
 *La función `submitEditPromotionForEdit` envía un formulario de promoción editado, actualiza la promoción el
 *el servidor, y luego cierra el modo de edición de promoción y recarga las promociones si la actualización es
 *exitoso.
 */
function submitEditPromotionForEdit() {
    var formData = new FormData(document.getElementById('editPromotionFormForEdit'));

    updatePromotionOnServerForEdit(formData).then(response => {
        if(response.success) {
            closeEditPromotionModalForEdit();
            reloadPromotionsForEdit();
        } else {
            console.error('Error al guardar la edición:', response.error);
        }
    }).catch(error => {
        console.error('Error al actualizar la promoción:', error);
    });
}




// Actualizar promoción en el servidor (en modo de edición)
/**
 *La función `updatePromotionOnServerForEdit` envía una solicitud POST a un archivo PHP para actualizar
 *promociones en el servidor y devuelve una promesa que se resuelve en la respuesta JSON del servidor.
 *
 *@return un objeto Promesa.
 */
function updatePromotionOnServerForEdit(formData) {
    var updateUrl = 'update_promotion_for_edit.php'; // URL a tu archivo PHP para actualizar promociones

    return fetch(updateUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Asumiendo que tu servidor responde con JSON.
    });
}






/**
 *La función fetchPromotionsFromDatabaseForEdit recupera promociones de una base de datos, maneja el
 *respuesta, y construye una tabla con los datos.
 *
 *@return La función fetchPromotionsFromDatabaseForEdit() devuelve una promesa.
 */
// Obtener promociones de la base de datos
function fetchPromotionsFromDatabaseForEdit() {
    return fetch('get_promotions.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            allPromotions = data; // Guardar los datos en la variable allPromotions
            buildTableForEdit(data); // Construir la tabla con los datos obtenidos
        })
        .catch(error => {
            handleError(error);
        });
}

/**
 *La función `handleErrorForEdit` registra un mensaje de error y permite un manejo de errores adicional en
 *el contexto de edición.
 */
function handleErrorForEdit(error) {
    console.error('Error en el contexto de edición:', error);
    // Aquí puedes agregar lógica adicional para manejar errores en el contexto de edición
}
/**
*La función reloadPromotionsForEdit busca promociones de una base de datos, crea una tabla para editar,
*y maneja cualquier error que ocurra.
*/
//Recargar promociones (en modo de edición)
function reloadPromotionsForEdit() {
    fetchPromotionsFromDatabaseForEdit().then(buildTableForEdit).catch(handleErrorForEdit);
}

/**
 *Este código define una función para recuperar datos de promoción por ID para editar y una función para abrir una
 *modalidad de promoción para edición.
 *
 *@return La función `getPromotionDataByIdForEdit` devuelve el objeto de datos de promoción con el
 *Coincide con la propiedad `idpromocion`.
 */
function getPromotionDataByIdForEdit(id) {
    return allPromotions.find(promotion => promotion.idpromocion == id);
}
var isEditMode = false;
function openPromotionModalForEdit() {
    isEditMode = true;
    openPromotionModal();
}

/**
 *La función closePromotionModalForEdit oculta el elemento promociónModalEdit.
 */
function closePromotionModalForEdit() {
    document.getElementById('promotionModalEdit').style.display = 'none';
}

//Botón para cerrar el modal de edición de promociones (en modo de edición)
/**
 *The function closeEditPromotionModalForEdit hides the editPromotionModalForEdit element.
 */
function closeEditPromotionModalForEdit() {
    document.getElementById('editPromotionModalForEdit').style.display = 'none';
}

/**
 *La función toggleActivePromotionsForEdit se utiliza para alternar la visibilidad de activos e inactivos.
 *promociones en un modal para edición.
 */
// Botón para alternar la visualización de promociones activas/inactivas (en modo de edición)
function toggleActivePromotionsForEdit() {
    // Código para alternar la visualización en el modal de edición
}


   // ************************************************* SCRIPTS PARA LOS BOTONES********************************************************
    // -------------------------------------------------------------categorias------------------------------------------------
/**
 *Este código   define dos funciones en PHP que abren y cierran un modal de categoría.
 */
function openCategoryModal() {
    document.getElementById("addCategoryModal").style.display = "block";
}

function closeCategoryModal() {
    document.getElementById("addCategoryModal").style.display = "none";
} 



    //------------------------------------------------ Familia ----------------------------------------------------------------
/**
 *Este código   define dos funciones en PHP que abren y cierran un modal en JavaScript.
 */
function openFamilyModal() {
    document.getElementById("addFamilyModal").style.display = "block";
}

function closeFamilyModal() {
    document.getElementById("addFamilyModal").style.display = "none";
}



    //------------------------------------------------- tipo ----------------------------------------------------------------
/**
 *Este código PHP   define dos funciones, `openTypeModal()` y `closeTypeModal()`, que
 *mostrar y ocultar respectivamente un modal con el id "addTypeModal".
 */
function openTypeModal() {
    document.getElementById("addTypeModal").style.display = "block";
}

function closeTypeModal() {
    document.getElementById("addTypeModal").style.display = "none";
}



    //------------------------------------------------- demanda ----------------------------------------------------------------
/**
 *Este código   define dos funciones en PHP que abren y cierran un modal de demanda.
 */
function openDemandModal() {
    document.getElementById("addDemandModal").style.display = "block";
}

function closeDemandModal() {
    document.getElementById("addDemandModal").style.display = "none";
}


// ************************************************* SCRIPTS PARA LOS BOTONES********************************************************
</script>

<!----------------------------------------------------------------BUSCAR-------------------------------------------------------->
<script>
/**
 *La función `buscarProducto` se utiliza para buscar un producto usando el valor ingresado en el input
 *campo con el id `terminoBusqueda`, y luego actualizar una tabla con los resultados de la búsqueda.
 */
function buscarProducto() {
    var terminoBusqueda = document.getElementById('terminoBusqueda').value;
    fetch('buscarProducto.php?terminoBusqueda=' + encodeURIComponent(terminoBusqueda))
    .then(response => response.json())
    .then(data => {
        actualizarTablaProductos(data);
    })
    .catch(error => console.error('Error:', error));
}

/**
 *La función "actualizarTablaProductos" actualiza el div "tablaProductos" con una nueva tabla basada en
 *los datos recibidos.
 */
function actualizarTablaProductos(data) {
    var tablaProductosDiv = document.getElementById('tablaProductos');
    
    // Limpia la tabla existente antes de añadir los nuevos resultados
    tablaProductosDiv.innerHTML = ''; 

    // Construye la nueva tabla basada en los datos recibidos
    var html = construirTablaProductos(data);
    tablaProductosDiv.innerHTML = html;
}

/**
 *La función `construirTablaProductos` toma una matriz de productos y construye una tabla HTML con
 *su información.
 *
 *@return una cadena HTML que representa una tabla de productos.
 */
function construirTablaProductos(productos) {
    var html = '<table border="1"><tr><th>ID Producto</th><th>Nombre</th><th>Stock</th><th>Precio Original</th><th>Precio con Descuento</th><th>Estado</th><th>Promoción</th><th>Categoría</th><th>Familia</th><th>Tipo</th><th>Acciones</th></tr>';
    productos.forEach(function(producto) {
        var precioOriginal = Math.round(producto.precio);
        var precioConDescuento = calcularPrecioConDescuento(producto.precio, producto.descuento);
        precioConDescuento = Math.round(precioConDescuento);

        html += "<tr>" +
                    "<td>" + producto.idProducto + "</td>" +
                    "<td>" + producto.nombre + "</td>" +
                    "<td>" + producto.stock + "</td>" +
                    "<td>$" + precioOriginal + "</td>" + // Redondear precio original
                    "<td>$" + precioConDescuento + "</td>" + // Redondear precio con descuento
                    "<td>" + producto.estado + "</td>" + 
                    "<td>" + producto.nombre_promocion + " (" + producto.descuento + "%)" + "</td>" +
                    "<td>" + producto.nombre_categoria + "</td>" +
                    "<td>" + producto.nombre_familia + "</td>" +
                    "<td>" + producto.nombre_tipo + "</td>" +
                    "<td><button onclick='openEditModal(" + producto.idProducto + ")'>Editar</button></td>" +
                "</tr>";
    });
    html += '</table>';
    return html;
}



/**
 *La función calcula el precio con descuento aplicado.
 *
 *@devuelve el precio con el descuento aplicado.
 */
function calcularPrecioConDescuento(precio, descuento) {
    var descuentoAplicado = precio * (descuento / 100);
    return precio - descuentoAplicado;
}


/**
 *La función alterna la visibilidad de los productos inactivos en una tabla según el valor del
 *mostrarvariable inactiva.
 */
function toggleActiveProducts() {
    var rows = document.querySelectorAll('#tablaProductos tr:not(:first-child)'); // Selecciona todas las filas excepto la cabecera

    rows.forEach(function(row) {
        var estado = row.cells[5] ? row.cells[5].innerText : '';

        if (estado.toLowerCase() === 'inactivo') {
            row.style.display = showInactive ? '' : 'none'; // Cambia la visibilidad según el estado de showInactive
        }
    });

    var button = document.getElementById('toggleActiveProducts');
    button.innerText = showInactive ? 'Mostrar Inactivos' : 'Ocultar Inactivos';
    showInactive = !showInactive; // Cambia el estado de showInactive
}



</script>


<script>

//*************************************************ajax ********************************************************
//AJAX para enviar la información al servidor
//parte de producto -----------------------------------------------------------------------------------------------------------------------------
//parte de categoria -----------------------------------------------------------------------------------------------------------------------------
/*Este código   está escrito en JavaScript y agrega un detector de eventos a un formulario con la identificación
"añadirCategoríaForm". Cuando se envía el formulario, evita el comportamiento de envío de formulario predeterminado.
Luego crea un nuevo objeto FormData a partir de los datos del formulario.*/
    document.getElementById("addCategoryForm").addEventListener("submit", function(event){
    event.preventDefault();
    
    var formData = new FormData(this);
    
    fetch('insert_category.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();  
    })
    .catch(error => {
        alert('Error al añadir categoría: ' + error);
    });
});

//parte de familia --------------------------------------------------------------------------------------------------------------------------------
/*Este código   está escrito en JavaScript y agrega un detector de eventos a un formulario con la identificación
"agregar formulario familiar". Cuando se envía el formulario, evita el comportamiento de envío de formulario predeterminado. Él
luego crea un nuevo objeto FormData a partir de los datos del formulario.*/
document.getElementById("addFamilyForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('insert_family.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); 
    })
    .catch(error => {
        alert('Error al añadir familia: ' + error);
    });
});
//parte de tipo --------------------------------------------------------------------------------------------------------------------------------
/*Este código   está escrito en JavaScript y agrega un detector de eventos a un formulario con la identificación
"añadirTypeForm". Cuando se envía el formulario, evita el comportamiento de envío de formulario predeterminado. entonces
Crea un nuevo objeto FormData a partir de los datos del formulario.*/
document.getElementById("addTypeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('insert_type.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); 
    })
    .catch(error => {
        alert('Error al añadir tipo: ' + error);
    });
});


/*Este código   está escrito en JavaScript y utiliza el evento DOMContentLoaded para ejecutar una
funcion cuando el documento HTML haya terminado de cargarse. */
document.addEventListener('DOMContentLoaded', function () {
    // Cargar las promociones al cargar la página
    loadPromotions();

    // Manejar el evento de envío del formulario
    document.getElementById('addProductForm').addEventListener('submit', function (event) {
        event.preventDefault();
        // Aquí manejar la lógica de envío del formulario
    });
});

</script>



</body>
</html>
