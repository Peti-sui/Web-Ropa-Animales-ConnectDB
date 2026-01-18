<?php
require "conexion.php";
/* Se incluye el archivo 'conexion.php' que contiene la conexión a la base de datos. */

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];
/* Se reciben los datos enviados por el formulario mediante el método POST:
   - id: identificador del producto a modificar
   - nombre: nuevo nombre del producto
   - precio: nuevo precio del producto
   - imagen: nueva URL o ruta de la imagen del producto
*/

if (empty($id) || empty($nombre) || empty($precio)) {
    die("Faltan datos obligatorios");
}
/* Validación de los campos obligatorios:
   - Si 'id', 'nombre' o 'precio' están vacíos, se detiene la ejecución con un mensaje de error.
   - 'imagen' no es obligatoria según esta validación.
*/

$sql = "UPDATE productos 
        SET nombre = ?, precio = ?, imagen = ?
        WHERE id = ?";
/* Se define la consulta SQL parametrizada para actualizar un producto específico:
   - Se utiliza '?' como placeholders para prevenir inyecciones SQL.
   - Se actualizarán los campos 'nombre', 'precio' e 'imagen' del producto con el id proporcionado.
*/

$stmt = $conn->prepare($sql);
/* Se prepara la consulta SQL utilizando la conexión a la base de datos ($conn).
   Esto devuelve un objeto statement ($stmt) que permitirá enlazar los parámetros.
*/

$stmt->bind_param("sdsi", $nombre, $precio, $imagen, $id);
/* Se enlazan los parámetros a la consulta preparada:
   - "s" -> string (nombre)
   - "d" -> double/decimal (precio)
   - "s" -> string (imagen)
   - "i" -> integer (id)
   Esto asegura que los datos sean tratados correctamente y protege contra inyecciones SQL.
*/

if ($stmt->execute()) {
    echo "Producto modificado correctamente";
} else {
    echo "Error al modificar el producto";
}
/* Se ejecuta la consulta:
   - Si la ejecución es exitosa, se muestra un mensaje de confirmación.
   - Si ocurre algún error, se muestra un mensaje de error.
*/
