<?php
/* Incluye el archivo de cabecera, que probablemente contiene la estructura HTML inicial y navegación */
include './includes/header.php';

/* Requiere el archivo de configuración de la conexión a la base de datos */
require "./config/conexion.php";

/* Comprueba si el formulario ha sido enviado mediante el método POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    /* Captura los valores enviados desde el formulario: nombre en español, nombre en inglés y precio */
    $nombre_es = $_POST['nombre_es'];
    $nombre_en = $_POST['nombre_en'];
    $precio = $_POST['precio'];

    /* Verifica si se ha subido un archivo y que no haya errores en la carga */
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        
        /* Define la carpeta de destino para almacenar la imagen */
        $carpeta_destino = "imagenes_db/";
        
        /* Crea la carpeta si no existe, con permisos 0777 y recursividad activada */
        if (!is_dir($carpeta_destino)) mkdir($carpeta_destino, 0777, true);

        /* Obtiene la extensión del archivo subido */
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

        /* Genera un nombre único para la imagen evitando colisiones */
        $nombre_archivo = uniqid() . "." . $extension;

        /* Ruta completa donde se guardará la imagen */
        $ruta_completa = $carpeta_destino . $nombre_archivo;

        /* Mueve la imagen desde la carpeta temporal a la carpeta de destino */
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
            
            /* Guarda la ruta de la imagen para insertarla en la base de datos */
            $imagen = $ruta_completa;

            /* Prepara la sentencia SQL para insertar el nuevo producto */
            $stmt = $conn->prepare("INSERT INTO productos (nombre_es, nombre_en, precio, imagen) VALUES (?, ?, ?, ?)");
            
            /* Vincula los parámetros de la consulta a las variables PHP */
            $stmt->bind_param("ssds", $nombre_es, $nombre_en, $precio, $imagen);
            
            /* Ejecuta la consulta y muestra un mensaje de éxito o error según corresponda */
            if ($stmt->execute()) {
                echo "<p style='color:green; text-align:center;'>";
                if($idioma == 'espanol') {
                    echo "Producto agregado correctamente.";
                } else {
                    echo "Product added successfully.";
                }
                echo "</p>";
            } else {
                echo "<p style='color:red; text-align:center;'>";
                if($idioma == 'espanol') {
                    echo "Error al agregar producto: " . $stmt->error;
                } else {
                    echo "Error adding product: " . $stmt->error;
                }
                echo "</p>";
            }

            /* Cierra la sentencia preparada */
            $stmt->close();
        } else {
            /* Mensaje de error si no se pudo mover la imagen a la carpeta destino */
            echo "<p style='color:red; text-align:center;'>";
            if($idioma == 'espanol') {
                echo "Error al subir la imagen.";
            } else {
                echo "Error uploading the image.";
            }
            echo "</p>";
        }
    } else {
        /* Mensaje de error si no se subió ningún archivo */
        echo "<p style='color:red; text-align:center;'>";
        if($idioma == 'espanol') {
            echo "No se ha subido ninguna imagen.";
        } else {
            echo "No image has been uploaded.";
        }
        echo "</p>";
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

    <!-- Enlaza estilos CSS principales y específicos de configuración -->
    <link rel="stylesheet" href="./src/styles/style.css">
    <link rel="stylesheet" href="./src/styles/styleSettings.css">
</head>
<body>
    <main>
        <fieldset>
            <legend>
                
                <?php if($idioma == 'espanol') {
                    echo 'Añadir Producto';
                } else {
                    echo 'Add Product';
                } ?>
            </legend>

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

            <form action="" method="post" enctype="multipart/form-data">
                <!-- Campo para el nombre en español -->
                <label>
                    <?php if($idioma == 'espanol') {
                        echo 'Nombre (Español):';
                    } else {
                        echo 'Name (Spanish):';
                    } ?>
                </label><br>
                <input type="text" name="nombre_es"><br><br>

                <!-- Campo para el nombre en inglés -->
                <label>
                    <?php if($idioma == 'espanol') {
                        echo 'Nombre (Inglés):';
                    } else {
                        echo 'Name (English):';
                    } ?>
                </label><br>
                <input type="text" name="nombre_en"><br><br>

                <!-- Campo para el precio -->
                <label>
                    <?php if($idioma == 'espanol') {
                        echo 'Precio:';
                    } else {
                        echo 'Price:';
                    } ?>
                </label><br>
                <input type="number" name="precio" step="0.01"><br><br>

                <!-- Campo para subir la imagen del producto -->
                <label>
                    <?php if($idioma == 'espanol') {
                        echo 'Imagen:';
                    } else {
                        echo 'Image:';
                    } ?>
                </label><br>
                <input type="file" name="imagen" accept="image/*"><br><br>

                <!-- Botón de envío -->
                <input type="submit" value="<?php echo ($idioma == 'espanol') ? 'Agregar Producto' : 'Add Product'; ?>">
            </form>
        </fieldset>

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

<!-- Incluye el pie de página -->
<?php include './includes/footer.php'; ?>
</html>
