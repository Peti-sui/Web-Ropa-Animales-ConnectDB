<?php
require "./config/conexion.php"; 
/* Se incluye el archivo de conexión a la base de datos, estableciendo $conn como objeto de conexión. */

// Gestión de Idioma
$idioma_actual = (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') ? 'espanol' : 'ingles';
/* Se determina el idioma actual según la cookie 'idiomita'. Por defecto será 'ingles' si no está definida o no es 'espanol'. */

// Funciones de Cookie
function recibirCookieDeseados() {
    /* Esta función devuelve un array con los IDs de productos deseados almacenados en la cookie 'listaDeseados'. */
    if (isset($_COOKIE['listaDeseados'])) {
        $list = json_decode($_COOKIE['listaDeseados'], true); 
        /* Se decodifica el JSON de la cookie en un array asociativo. */
        return is_array($list) ? $list : []; 
        /* Se asegura que siempre se retorne un array. */
    }
    return []; 
    /* Si la cookie no existe, se retorna un array vacío. */
}

function guardarCookieDeseados($list) {
    /* Esta función guarda el array $list en la cookie 'listaDeseados' como JSON válido. */
    setcookie('listaDeseados', json_encode(array_values($list)), time() + (86400 * 30), "/");
    /* La cookie tendrá una duración de 30 días y estará disponible en toda la web. */
}

$listaDeseados = recibirCookieDeseados();
/* Se obtiene la lista de productos deseados al cargar la página. */

// --- PROCESAR POST (Añadir, Eliminar, Vaciar) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // AÑADIR (Viene de principal.php)
    if (isset($_POST['añadir_producto_lista'], $_POST['producto_id'])) {
        $id_nuevo = (int)$_POST['producto_id']; 
        /* Se convierte el ID recibido a entero para mayor seguridad. */
        if (!in_array($id_nuevo, $listaDeseados)) {
            $listaDeseados[] = $id_nuevo; 
            /* Se añade el producto a la lista si no estaba ya presente. */
            guardarCookieDeseados($listaDeseados); 
            /* Se actualiza la cookie con la nueva lista. */
        }
    }

    // ELIMINAR UNO
    if (isset($_POST['remove_wish'], $_POST['producto_id'])) {
        $id_eliminar = (int)$_POST['producto_id']; 
        /* ID del producto que se desea eliminar. */
        $posicion = array_search($id_eliminar, $listaDeseados); 
        /* Se busca la posición del producto en la lista. */
        if ($posicion !== false) {
            unset($listaDeseados[$posicion]); 
            /* Se elimina el producto de la lista. */
            guardarCookieDeseados($listaDeseados); 
            /* Se actualiza la cookie. */
        }
    }

    // VACIAR TODO
    if (isset($_POST['clear_list'])) {
        $listaDeseados = []; 
        /* Se vacía el array de productos deseados. */
        guardarCookieDeseados([]); 
        /* Se actualiza la cookie con un array vacío. */
    }

    // Redirigir siempre después de un POST para que la cookie se actualice en el navegador
    header("Location: listaDeseados.php");
    exit;
    /* Previene reenvíos de formularios y asegura que la cookie se aplique correctamente en el navegador. */
}

// 2. OBTENER PRODUCTOS DE LA BASE DE DATOS
$productos_db = [];
if (!empty($listaDeseados)) {
    $ids_limpios = implode(',', array_map('intval', $listaDeseados)); 
    /* Se convierte la lista de IDs en una cadena separada por comas, asegurando que todos sean enteros. */
    $sql = "SELECT * FROM productos WHERE id IN ($ids_limpios)";
    $result = $conn->query($sql); 
    /* Consulta a la base de datos para obtener los productos de la lista de deseados. */
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos_db[$row['id']] = $row; 
            /* Se almacena cada producto en un array asociativo usando su ID como clave. */
        }
    }
}

include './includes/header.php'; 
/* Se incluye el encabezado de la página. */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/styles/style.css">
    <title>Mi Lista de Deseados</title>
</head>
<body>
<main class="contenido">
    <fieldset class="contenedor-lista">
        <legend><?php echo ($idioma_actual == 'espanol') ? "Mi Lista de Deseados" : "My Wishlist"; ?></legend>
       

        <div class="wishlist-items">
            <?php 
            $hay_productos = false; 
            /* Bandera para saber si hay productos en la lista. */
            foreach ($listaDeseados as $id_id):
                if (isset($productos_db[$id_id])):
                    $hay_productos = true; 
                    /* Si el producto existe en la DB, se marca que hay productos. */
                    $p = $productos_db[$id_id];
                    $nombre = ($idioma_actual == 'espanol') ? $p['nombre_es'] : $p['nombre_en']; 
                    /* Selección del nombre del producto según el idioma. */
            ?>
                <div class="item-fila">
                    <div class="info-basica">
                        <img src="<?php echo $p['imagen']; ?>" alt="img">
                        <div>
                            <h4><?php echo $nombre; ?></h4>
                            <p><?php echo number_format($p['precio'], 2); ?> €</p>
                     
                        </div>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                        <button type="submit" name="remove_wish" class="btn-rojo">
                            <?php echo ($idioma_actual == 'espanol') ? "Eliminar" : "Remove"; ?>
                         
                        </button>
                    </form>
                </div>
            <?php endif; endforeach; ?>

            <?php if (!$hay_productos): ?>
                <p style="text-align:center; padding:20px;"><?php echo ($idioma_actual == 'espanol') ? "Tu lista está vacía." : "Your wishlist is empty."; ?></p>
             
            <?php endif; ?>
        </div>

        <?php if ($hay_productos): ?>
        <div class="seccion-final" style="margin-top: 30px;">
            <form method="POST">
                <button type="submit" name="clear_list" class="btn-gris" style="background:#333;">
                    <?php echo ($idioma_actual == 'espanol') ? "Vaciar Lista Completa" : "Clear All"; ?>
                  
                </button>
            </form>
        </div>
        <?php endif; ?>
    </fieldset>
</main>

    <div style="text-align: center; margin-top: 20px;">
        <a href="principal.php" style="color: #007bff; text-decoration: none;">
            ← <?php echo ($idioma_actual == 'espanol') ? "Volver a la tienda" : "Back to shop"; ?>
        
        </a>
    </div>

    <!-- Kevin te dejar ir -->
<?php include './includes/footer.php'; 
/* Se incluye el footer de la página. */
?>
</body>
</html>
