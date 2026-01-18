<?php
/* Se incluye el archivo de conexión a la base de datos */
require "conexion.php";

/* Se obtiene el valor del campo 'nombre' enviado mediante el método POST */
$nombre = $_POST['nombre'];

/* Validación para asegurarse de que se ha proporcionado un nombre */
if (empty($nombre)) {
    /* Termina la ejecución del script si el nombre está vacío y muestra un mensaje de error */
    die("Error: Debes proporcionar el nombre del producto para eliminarlo.");
}

/* Consulta SQL para eliminar un producto específico basado en su nombre usando un marcador de posición */
$sql = "DELETE FROM productos WHERE nombre = ?";

/* Preparación de la sentencia SQL para prevenir inyecciones SQL */
$stmt = $conn->prepare($sql);

/* Se enlaza el parámetro '$nombre' a la sentencia preparada como tipo string ('s') */
$stmt->bind_param("s", $nombre); 

/* Ejecución de la sentencia preparada */
if ($stmt->execute()) {
    /* Se verifica si se afectó alguna fila, es decir, si se eliminó algún producto */
    if ($stmt->affected_rows > 0) {
        /* Mensaje de éxito si se eliminó al menos un producto */
        echo "Producto '$nombre' eliminado correctamente.";
    } else {
        /* Mensaje informativo si no se encontró ningún producto con ese nombre */
        echo "No se encontró ningún producto con el nombre: $nombre";
    }
} else {
    /* Mensaje de error si hubo algún problema al ejecutar la sentencia SQL */
    echo "Error al intentar eliminar el producto.";
}

/* Cierre de la sentencia preparada para liberar recursos */
$stmt->close();

/* Cierre de la conexión a la base de datos */
$conn->close();
?>
