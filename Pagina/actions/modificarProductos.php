<?php
require "conexion.php";

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];

if (empty($id) || empty($nombre) || empty($precio)) {
    die("Faltan datos obligatorios");
}

$sql = "UPDATE productos 
        SET nombre = ?, precio = ?, imagen = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdsi", $nombre, $precio, $imagen, $id);

if ($stmt->execute()) {
    echo "Producto modificado correctamente";
} else {
    echo "Error al modificar el producto";
}
