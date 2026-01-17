<?php
include './header.php';
require "conexion.php";

// ======================= GUARDAR PRODUCTO =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_es'])) {
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
            $stmt->execute();
            $stmt->close();

            // Recargar la página para ver el producto agregado
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color:red; text-align:center;'>Error al subir la imagen.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>No se ha subido ninguna imagen.</p>";
    }
}
// =================================================================

// Idioma
$cookie_name = 'listaDeseados';
$otra_lengua = 'ingles';
$idioma_actual = 'ingles';
if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    $otra_lengua = 'espanol';
    $idioma_actual = 'espanol';
}

// Obtener productos
$sql = "SELECT * FROM productos ORDER BY id DESC";
$result = $conn->query($sql);
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body>
<main>



<!-- LISTA DE PRODUCTOS -->
<div class="contenido">
<?php if (!empty($productos)): ?>
    <?php foreach ($productos as $id => $producto): 
        $nombreProducto = ($idioma_actual == 'espanol') ? $producto['nombre_es'] : $producto['nombre_en'];
    ?>
        <div class="producto">
            <div class="product-image-container">
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $nombreProducto; ?>">
            </div>
            <h3><?php echo $nombreProducto; ?></h3>
            <p><strong><?php echo ($idioma_actual == 'espanol') ? 'Precio:' : 'Price:'; ?> <?php echo number_format($producto['precio'],2); ?> €</strong></p>
                <button class="agregar-al-carrito">
                    <?php 
                    echo ($idioma_actual == 'espanol') ? 
                            "Añadir al carrito" : 
                            "Add to cart"; 
                    ?>
                </button>

                <form method="POST" action="listaDeseados.php">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <button type="submit" name="añadir_producto_lista">
                        <?php echo ($idioma_actual == 'espanol') ? "Añadir a Deseados" : "Add to Wishlist"; ?>
                    </button>
                </form>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center;"><?php echo ($idioma_actual == 'espanol') ? 'No hay productos agregados aún.' : 'No products added yet.'; ?></p>
<?php endif; ?>
</div>

<p style="text-align: center;">
        <?php 
            echo ($idioma_actual == 'espanol') 
                ? "Página en construcción."
                : "Under construction.";
        ?>
        <?php if ($tema === 'oscuro'): ?>
            <img style="width: 100px; display: block; margin: 10px auto;" src="https://i.pinimg.com/originals/8e/9d/47/8e9d4763350b27ca7ef4d32921528470.gif" alt="zi">
        <?php else: ?>
            <img style="width: 100px; display: block; margin: 10px auto;" src="https://i.pinimg.com/originals/e5/49/b1/e549b1e2cb82f0b15f69eb6f57ba7581.gif" alt="zi">
        <?php endif; ?>
</p>

</main>
<?php include './footer.php'; ?>
</body>
</html>

