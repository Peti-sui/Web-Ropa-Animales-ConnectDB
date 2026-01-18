<?php
require "conexion.php";

$nombre = $_POST['nombre'];

if (empty($nombre)) {
    die("Error: Debes proporcionar el nombre del producto para eliminarlo.");
}

$sql = "DELETE FROM productos WHERE nombre = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre); 

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Producto '$nombre' eliminado correctamente.";
    } else {
        echo "No se encontró ningún producto con el nombre: $nombre";
    }
} else {
    echo "Error al intentar eliminar el producto.";
}

$stmt->close();
$conn->close();
?>
