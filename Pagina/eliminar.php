<?php
/* Incluye el archivo header.php que probablemente contiene la cabecera HTML y navegación */
include './includes/header.php';

/* Requiere la conexión a la base de datos desde el archivo de configuración */
require "./config/conexion.php";

/* Comprueba si el método de la petición HTTP es POST, lo que indica que se ha enviado el formulario */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Captura los valores enviados desde el formulario mediante POST */
    $nombre_es = $_POST['nombre_es'];
    $nombre_en = $_POST['nombre_en'];

    /* Verifica si ambos campos están vacíos */
    if (empty($nombre_es) && empty($nombre_en)) {
        /* Muestra un mensaje de error en rojo */
        echo "<p style='color:red;'>";
        if($idioma == 'espanol') {
            /* Mensaje de error en español */
            echo "Error: Debes rellenar al menos uno de los dos nombres para buscar el producto.";
        } else {
            /* Mensaje de error en inglés */
            echo "Error: You must fill in at least one of the two names to search for the product.";
        }
        echo "</p>";
    } else {
        /* Prepara la sentencia SQL para eliminar productos que coincidan con los nombres proporcionados */
        $stmt = $conn->prepare("DELETE FROM productos WHERE nombre_es = ? OR nombre_en = ?");
        /* Vincula los parámetros a la consulta preparada */
        $stmt->bind_param("ss", $nombre_es, $nombre_en);
        
        /* Ejecuta la consulta preparada */
        if ($stmt->execute()) {
            /* Comprueba si se eliminaron productos */
            if ($stmt->affected_rows > 0) {
                /* Muestra mensaje de éxito en verde y centrado */
                echo "<p style='color:green; text-align:center;'>";
                if($idioma == 'espanol') {
                    /* Mensaje en español indicando cuántos productos se eliminaron */
                    echo "Se han eliminado " . $stmt->affected_rows . " producto(s) correctamente.";
                } else {
                    /* Mensaje en inglés indicando cuántos productos se eliminaron */
                    echo $stmt->affected_rows . " product(s) have been deleted successfully.";
                }
                echo "</p>";
            } else {
                /* Mensaje de advertencia en naranja si no se encontró ningún producto */
                echo "<p style='color:orange; text-align:center;'>";
                if($idioma == 'espanol') {
                    echo "No se encontró ningún producto que coincida con esos nombres.";
                } else {
                    echo "No product was found matching those names.";
                }
                echo "</p>";
            }
        } else {
            /* Mensaje de error en rojo si la consulta falló */
            echo "<p style='color:red; text-align:center;'>";
            if($idioma == 'espanol') {
                echo "Error al eliminar el producto: " . $stmt->error;
            } else {
                echo "Error deleting the product: " . $stmt->error;
            }
            echo "</p>";
        }
        /* Cierra la sentencia preparada */
        $stmt->close();
    }
    /* Cierra la conexión a la base de datos */
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluye los estilos CSS del proyecto -->
    <link rel="stylesheet" href="./src/styles/style.css">
    <link rel="stylesheet" href="./src/styles/styleSettings.css">
</head>
<body>
    <main>
        <fieldset>
            <legend>
                <?php if($idioma == 'espanol') {
                    echo 'Eliminar Producto';
                } else {
                    echo 'Delete Product';
                } ?>
            </legend>

            <!-- Texto de instrucción para el formulario, cambia el color según el tema -->
            <p style="<?php if($tema == 'oscuro'){
                echo "color: white;";
            } else {
                echo "color: black;";
            } ?>">
                <?php if($idioma == 'espanol') {
                    echo 'Introduce el nombre en español <strong>o</strong> en inglés para eliminar el producto:';
                } else {
                    echo 'Enter the name in Spanish <strong>or</strong> English to delete the product:';
                } ?>
            </p>

            <!-- Formulario para eliminar productos -->
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

                <!-- Botón de envío con confirmación de acción según idioma -->
                <input type="submit" 
                value="<?php echo ($idioma == 'espanol') ? 'Eliminar Producto' : 'Delete Product'; ?>" 
                onclick="return confirm('<?php echo ($idioma == 'espanol') ? '¿Estás seguro de que deseas eliminar este producto?' : 'Are you sure you want to delete this product?'; ?>');">

            </form>
        </fieldset>

        <!-- Mensaje de bienvenida al administrador con imagen según tema -->
        <p style="text-align: center;">
                <?php 
                    echo ($idioma == 'espanol') 
                        ? "Hola admin :D."
                        : "Hello admin :D.";
                ?>
                <?php if ($tema === 'oscuro'): ?>
                    <img style="width: 100px; display: block; margin: 10px auto;" src="https://i.pinimg.com/originals/8e/9d/47/8e9d4763350b27ca7ef4d32921528470.gif" alt="zi">
                <?php else: ?>
                    <img style="width: 100px; display: block; margin: 10px auto;" src="https://i.pinimg.com/originals/e5/49/b1/e549b1e2cb82f0b15f69eb6f57ba7581.gif" alt="zi">
                <?php endif; ?>
        </p>

    </main>
</body>
<!-- Incluye el archivo footer.php que probablemente contiene el pie de página -->
<?php include './includes/footer.php'; ?>
</html>
