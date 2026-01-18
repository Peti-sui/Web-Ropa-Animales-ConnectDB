<?php
/* Incluir la cabecera del sitio web */
include './includes/header.php';

/* Incluir la conexión a la base de datos */
require "./config/conexion.php";

// ======================= GUARDAR PRODUCTO =======================
/* Verifica si se envió un formulario POST con el campo 'nombre_es' */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_es'])) {
    /* Obtener los datos del formulario */
    $nombre_es = $_POST['nombre_es'];
    $nombre_en = $_POST['nombre_en'];
    $precio = $_POST['precio'];

    /* Comprobar si se ha subido un archivo de imagen correctamente */
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        /* Definir carpeta de destino para almacenar imágenes */
        $carpeta_destino = "imagenes_db/";
        /* Crear la carpeta si no existe */
        if (!is_dir($carpeta_destino)) mkdir($carpeta_destino, 0777, true);

        /* Obtener la extensión del archivo subido */
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        /* Generar un nombre único para la imagen */
        $nombre_archivo = uniqid() . "." . $extension;
        $ruta_completa = $carpeta_destino . $nombre_archivo;

        /* Mover la imagen desde la ubicación temporal a la carpeta destino */
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
            $imagen = $ruta_completa;

            /* Preparar la consulta SQL para insertar el producto */
            $stmt = $conn->prepare("INSERT INTO productos (nombre_es, nombre_en, precio, imagen) VALUES (?, ?, ?, ?)");
            /* Vincular parámetros a la consulta */
            $stmt->bind_param("ssds", $nombre_es, $nombre_en, $precio, $imagen);
            /* Ejecutar la consulta */
            $stmt->execute();
            /* Cerrar la consulta preparada */
            $stmt->close();

            /* Recargar la página para mostrar el nuevo producto agregado */
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            /* Mensaje de error si la imagen no se pudo subir */
            echo "<p style='color:red; text-align:center;'>Error al subir la imagen.</p>";
        }
    } else {
        /* Mensaje de error si no se seleccionó ninguna imagen */
        echo "<p style='color:red; text-align:center;'>No se ha subido ninguna imagen.</p>";
    }
}
// =================================================================

/* Configuración de idioma */
$cookie_name = 'listaDeseados';
$otra_lengua = 'ingles';
$idioma_actual = 'ingles';

/* Verificar si la cookie 'idiomita' está establecida en español */
if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    $otra_lengua = 'espanol';
    $idioma_actual = 'espanol';
}

/* Obtener todos los productos de la base de datos ordenados por ID descendente */
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
    <!-- Enlazar hoja de estilos CSS -->
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body>
<main>

<!-- LISTA DE PRODUCTOS -->
<div class="contenido">
<?php if (!empty($productos)): ?>
    <?php foreach ($productos as $id => $producto): 
        /* Determinar el nombre del producto según el idioma actual */
        $nombreProducto = ($idioma_actual == 'espanol') ? $producto['nombre_es'] : $producto['nombre_en'];
    ?>
        <div class="producto">
            <div class="product-image-container">
                <!-- Mostrar la imagen del producto -->
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $nombreProducto; ?>">
            </div>
            <!-- Mostrar el nombre del producto -->
            <h3><?php echo $nombreProducto; ?></h3>
            <!-- Mostrar el precio del producto -->
            <p><strong><?php echo ($idioma_actual == 'espanol') ? 'Precio:' : 'Price:'; ?> <?php echo number_format($producto['precio'],2); ?> €</strong></p>
            
            <!-- Formulario para añadir el producto al carrito -->
            <form method="POST" action="carrito.php">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <button type="submit" name="añadir_producto_carrito" class="agregar-al-carrito">
                    <?php echo ($idioma_actual == 'espanol') ? "Añadir al carrito" : "Add to cart"; ?>
                </button>
            </form>

            <!-- Formulario para añadir el producto a la lista de deseados -->
            <form method="POST" action="listaDeseados.php">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <button type="submit" name="añadir_producto_lista">
                    <?php echo ($idioma_actual == 'espanol') ? "Añadir a Deseados" : "Add to Wishlist"; ?>
                </button>
            </form>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <!-- Mensaje si no hay productos en la base de datos -->
    <p style="text-align:center;"><?php echo ($idioma_actual == 'espanol') ? 'No hay productos agregados aún.' : 'No products added yet.'; ?></p>
<?php endif; ?>
</div>

</main>
<!-- Incluir el footer del sitio web -->
<?php include './includes/footer.php'; ?>
</body>
</html>
