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
                echo "<p style='color:green;'>";
                if($idioma == 'espanol') {
                    echo "Producto agregado correctamente.";
                } else {
                    echo "Product added successfully.";
                }
                echo "</p>";
            } else {
                echo "<p style='color:red;'>";
                if($idioma == 'espanol') {
                    echo "Error al agregar producto: " . $stmt->error;
                } else {
                    echo "Error adding product: " . $stmt->error;
                }
                echo "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;'>";
            if($idioma == 'espanol') {
                echo "Error al subir la imagen.";
            } else {
                echo "Error uploading the image.";
            }
            echo "</p>";
        }
    } else {
        echo "<p style='color:red;'>";
        if($idioma == 'espanol') {
            echo "No se ha subido ninguna imagen.";
        } else {
            echo "No image has been uploaded.";
        }
        echo "</p>";
    }
    $conn->close();
}
?>

<h2>
    <?php if($idioma == 'espanol') {
        echo 'Añadir Producto';
    } else {
        echo 'Add Product';
    } ?>
</h2>

<p>
    <?php if($idioma == 'espanol') {
        echo 'Introduce el nombre en español <strong>o</strong> en inglés para eliminar el producto:';
    } else {
        echo 'Enter the name in Spanish <strong>or</strong> English to delete the product:';
    } ?>
</p>

<form action="" method="post" enctype="multipart/form-data">
    <label>
        <?php if($idioma == 'espanol') {
            echo 'Nombre (Español):';
        } else {
            echo 'Name (Spanish):';
        } ?>
    </label><br>
    <input type="text" name="nombre_es"><br><br>

    <label>
        <?php if($idioma == 'espanol') {
            echo 'Nombre (Inglés):';
        } else {
            echo 'Name (English):';
        } ?>
    </label><br>
    <input type="text" name="nombre_en"><br><br>

    <label>
        <?php if($idioma == 'espanol') {
            echo 'Precio:';
        } else {
            echo 'Price:';
        } ?>
    </label><br>
    <input type="number" name="precio" step="0.01"><br><br>

    <label>
        <?php if($idioma == 'espanol') {
            echo 'Imagen:';
        } else {
            echo 'Image:';
        } ?>
    </label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <input type="submit" value="<?php echo ($idioma == 'espanol') ? 'Agregar Producto' : 'Add Product'; ?>">

</form>
<?php include './footer.php'; ?>
