<?php
include './header.php';
require "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_es = $_POST['nombre_es'];
    $nombre_en = $_POST['nombre_en'];
    $precio = $_POST['precio'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpeta_destino = "imagenes/";
        if (!is_dir($carpeta_destino)) mkdir($carpeta_destino, 0777, true);

        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . "." . $extension;
        $ruta_completa = $carpeta_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
            $imagen = $ruta_completa;

            $stmt = $conn->prepare("INSERT INTO productos (nombre_es, nombre_en, precio, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $nombre_es, $nombre_en, $precio, $imagen);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Producto agregado correctamente.</p>";
            } else {
                echo "<p style='color:red;'>Error al agregar producto: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;'>Error al subir la imagen.</p>";
        }
    } else {
        echo "<p style='color:red;'>No se ha subido ninguna imagen.</p>";
    }

    $conn->close();
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <label>Nombre (Español):</label><br>
    <input type="text" name="nombre_es" required><br><br>

    <label>Nombre (Inglés):</label><br>
    <input type="text" name="nombre_en" required><br><br>

    <label>Precio:</label><br>
    <input type="number" name="precio" step="0.01" required><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <input type="submit" value="Agregar Producto">
</form>
<?php include './footer.php'; ?>