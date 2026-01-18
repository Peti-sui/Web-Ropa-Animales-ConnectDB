<?php
require "./config/conexion.php"; 
/* Se incluye el archivo de conexión a la base de datos. 
   Esto permite que $conn esté disponible para consultas posteriores. */

// 1. Gestión de Idioma
$idioma_actual = (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') ? 'espanol' : 'ingles';
/* Determina el idioma actual de la interfaz basado en la cookie 'idiomita'. 
   Si no existe o no es 'espanol', se establece 'ingles' por defecto. */

// 2. Funciones de Cookie
function recibirCookieCarrito() {
    /* Recupera la lista de productos del carrito almacenada en la cookie 'listaCarrito'.
       Decodifica el JSON a un array asociativo. 
       Si no existe o no es un array válido, devuelve un array vacío. */
    if (isset($_COOKIE['listaCarrito'])) {
        $list = json_decode($_COOKIE['listaCarrito'], true);
        return is_array($list) ? $list : [];
    }
    return [];
}

function guardarCookieCarrito($list) {
    /* Guarda el carrito en una cookie llamada 'listaCarrito'.
       Convierte el array a JSON y establece expiración a 30 días. */
    setcookie('listaCarrito', json_encode($list), time() + (86400 * 30), "/");
}

$listaCarrito = recibirCookieCarrito();
/* Obtiene la lista actual del carrito desde la cookie para manipularla durante la sesión. */

$productos_db = [];
if (!empty($listaCarrito)) {
    /* Si el carrito tiene productos, obtiene sus datos desde la base de datos. */
    $ids_limpios = implode(',', array_map('intval', array_keys($listaCarrito)));
    /* Sanitiza los IDs del carrito convirtiéndolos a enteros para prevenir inyección SQL. */
    $sql = "SELECT * FROM productos WHERE id IN ($ids_limpios)";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos_db[$row['id']] = $row;
            /* Almacena cada producto en un array asociativo con la clave siendo el ID */
        }
    }
}

// --- 4. PROCESAR POST (Ahora con datos disponibles) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Detecta si se ha enviado un formulario mediante POST para procesar acciones del carrito. */

    if (isset($_POST['realizar_pedido'])) {
        /* Procesa la compra: guarda cada producto en la tabla 'compras'. */
        foreach ($listaCarrito as $id_db => $cant) {
            if (isset($productos_db[$id_db])) {
                $p = $productos_db[$id_db];
                $precio_total = $p['precio'] * $cant;

                $stmt = $conn->prepare("INSERT INTO compras (nombre_es, nombre_en, cantidad, precio_total) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssid", $p['nombre_es'], $p['nombre_en'], $cant, $precio_total);
                $stmt->execute();
                $stmt->close();
            }
        }
    
        // Vaciar carrito después de guardar en BD
        guardarCookieCarrito([]);
    
        echo "<script>alert('" . (($idioma_actual=='espanol') ? "Compra realizada, disfrute los productos!!" : "Purchase completed, enjoy your products!!") . "'); window.location='carrito.php';</script>";
        exit;
    }

    // Añadir producto
    if (isset($_POST['añadir_producto_carrito'], $_POST['producto_id'])) {
        $id_nuevo = (int)$_POST['producto_id'];
        if (!isset($listaCarrito[$id_nuevo])) {
            $listaCarrito[$id_nuevo] = 1;
            guardarCookieCarrito($listaCarrito);
        }
    }

    // Eliminar producto
    if (isset($_POST['remove_cart'], $_POST['producto_id'])) {
        $id_eliminar = (int)$_POST['producto_id'];
        if (isset($listaCarrito[$id_eliminar])) {
            unset($listaCarrito[$id_eliminar]);
            guardarCookieCarrito($listaCarrito);
        }
    }

    // Vaciar carrito manual
    if (isset($_POST['clear_cart'])) {
        guardarCookieCarrito([]);
    }

    // Incrementar / Decrementar cantidad
    if (isset($_POST['cantidad_action'], $_POST['producto_id'])) {
        $id = (int)$_POST['producto_id'];
        if (isset($listaCarrito[$id])) {
            if ($_POST['cantidad_action'] === 'increment') {
                $listaCarrito[$id]++;
            } elseif ($_POST['cantidad_action'] === 'decrement') {
                $listaCarrito[$id] = max(1, $listaCarrito[$id]-1);
            }
            guardarCookieCarrito($listaCarrito);
        }
    }

    header("Location: ./carrito.php");
    exit;
    /* Redirige al carrito después de cualquier acción POST para evitar reenvíos. */
}

include './includes/header.php'; 
/* Incluye la cabecera HTML y los recursos compartidos (CSS, JS, menú, etc.) */
?>

<!-- El resto de tu HTML permanece exactamente igual -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./css/styleSettings.css">
<link rel="stylesheet" href="./css/carrito.css">
</head>
<body>
<main class="contenido">
    <fieldset class="contenedor-lista">
        <legend><?php echo ($idioma_actual=='espanol') ? "Tu Cesta de la Compra" : "Your Shopping Cart"; ?></legend>
        <!-- Título dinámico según idioma -->

        <div id="items-del-carrito">
            <?php 
            $hay_productos = false;
            $total_precio = 0;
            $cantidad_total = 0;
            /* Inicializa variables de control y totales */

            foreach ($listaCarrito as $id_id => $cant):
                if (isset($productos_db[$id_id])):
                    $hay_productos = true;
                    $p = $productos_db[$id_id];
                    $nombre = ($idioma_actual=='espanol') ? $p['nombre_es'] : $p['nombre_en'];
                    $subtotal = $p['precio'] * $cant;
                    $total_precio += $subtotal;
                    $cantidad_total += $cant;
                    /* Calcula subtotal, suma totales y determina el idioma del nombre del producto */
            ?>
            <div class="item-fila">
                <div class="info-basica">
                    <img src="<?php echo $p['imagen']; ?>" alt="img">
                    <div>
                        <h4><?php echo $nombre; ?></h4>
                        <p><?php echo number_format($p['precio'],2); ?> €</p>
                        <div class="controles-cantidad">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                                <button type="submit" name="cantidad_action" value="decrement" class="btn-qty">-</button>
                            </form>
                            <span style="margin:0 10px;"><?php echo $cant; ?></span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                                <button type="submit" name="cantidad_action" value="increment" class="btn-qty">+</button>
                            </form>
                            <span class="subtotal-texto"><?php echo number_format($subtotal,2); ?> €</span>
                        </div>
                    </div>
                </div>
                <form method="POST">
                    <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                    <button type="submit" name="remove_cart" class="btn-rojo">
                        <?php echo ($idioma_actual=='espanol') ? "Eliminar" : "Remove"; ?>
                    </button>
                </form>
            </div>
            <?php endif; endforeach; ?>
        </div>

        <?php if ($hay_productos): ?>
        <div class="seccion-final">
            <p><?php echo ($idioma_actual=='espanol') ? "Cantidad Total: " : "Total Quantity: "; ?><span><?php echo $cantidad_total; ?></span></p>
            <p><?php echo ($idioma_actual=='espanol') ? "Precio Total: " : "Total Price: "; ?><span><?php echo number_format($total_precio,2); ?> €</span></p>
            <form method="POST" class="mt-10">
                <button type="submit" name="realizar_pedido" class="btn-azul"><?php echo ($idioma_actual == 'espanol') ? "Realizar Pedido" : "Place Order"; ?></button>
            </form>
            <form method="POST" class="mt-10">
                <button type="submit" name="clear_cart" class="btn-gris"><?php echo ($idioma_actual == 'espanol') ? "Vaciar Carrito" : "Clear Cart"; ?></button>
            </form>
        </div>
        <?php endif; ?>
    </fieldset>
    <?php include './includes/footer.php' ?>
</main>
</body>
</html>
