<?php
include './header.php';
require "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_es = $_POST['nombre_es'];
    $nombre_en = $_POST['nombre_en'];

    if (empty($nombre_es) && empty($nombre_en)) {
        echo "<p style='color:red;'>";
        if($idioma == 'espanol') {
            echo "Error: Debes rellenar al menos uno de los dos nombres para buscar el producto.";
        } else {
            echo "Error: You must fill in at least one of the two names to search for the product.";
        }
        echo "</p>";
    } else {
        $stmt = $conn->prepare("DELETE FROM productos WHERE nombre_es = ? OR nombre_en = ?");
        $stmt->bind_param("ss", $nombre_es, $nombre_en);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<p style='color:green;'>";
                if($idioma == 'espanol') {
                    echo "Se han eliminado " . $stmt->affected_rows . " producto(s) correctamente.";
                } else {
                    echo $stmt->affected_rows . " product(s) have been deleted successfully.";
                }
                echo "</p>";
            } else {
                echo "<p style='color:orange;'>";
                if($idioma == 'espanol') {
                    echo "No se encontró ningún producto que coincida con esos nombres.";
                } else {
                    echo "No product was found matching those names.";
                }
                echo "</p>";
            }
        } else {
            echo "<p style='color:red;'>";
            if($idioma == 'espanol') {
                echo "Error al eliminar el producto: " . $stmt->error;
            } else {
                echo "Error deleting the product: " . $stmt->error;
            }
            echo "</p>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<h2>
    <?php if($idioma == 'espanol') {
        echo 'Eliminar Producto';
    } else {
        echo 'Delete Product';
    } ?>
</h2>

<p>
    <?php if($idioma == 'espanol') {
        echo 'Introduce el nombre en español <strong>o</strong> en inglés para eliminar el producto:';
    } else {
        echo 'Enter the name in Spanish <strong>or</strong> English to delete the product:';
    } ?>
</p>

<form action="" method="post">
    <label>
        <?php if($idioma == 'espanol') {
            echo 'Nombre en Español:';
        } else {
            echo 'Name in Spanish:';
        } ?>
    </label>
    <br>
    <input type="text" name="nombre_es"><br><br>

    <label>
        <?php if($idioma == 'espanol') {
            echo 'Nombre en Inglés:';
        } else {
            echo 'Name in English:';
        } ?>
    </label>
    <br>
    <input type="text" name="nombre_en"><br><br>

    <input type="submit" 
    value="<?php echo ($idioma == 'espanol') ? 'Eliminar Producto' : 'Delete Product'; ?>" 
    onclick="return confirm('<?php echo ($idioma == 'espanol') ? '¿Estás seguro de que deseas eliminar este producto?' : 'Are you sure you want to delete this product?'; ?>');"
>

</form>

<?php include './footer.php'; ?>

