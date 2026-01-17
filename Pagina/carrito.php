<?php
require "conexion.php";

// 1. Gestión de Idioma
$idioma_actual = (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') ? 'espanol' : 'ingles';

// 2. Funciones de Cookie
function recibirCookieCarrito() {
    if (isset($_COOKIE['listaCarrito'])) {
        $list = json_decode($_COOKIE['listaCarrito'], true);
        return is_array($list) ? $list : [];
    }
    return [];
}

function guardarCookieCarrito($list) {
    setcookie('listaCarrito', json_encode($list), time() + (86400 * 30), "/");
}

$listaCarrito = recibirCookieCarrito();


$productos_db = [];
if (!empty($listaCarrito)) {
    $ids_limpios = implode(',', array_map('intval', array_keys($listaCarrito)));
    $sql = "SELECT * FROM productos WHERE id IN ($ids_limpios)";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos_db[$row['id']] = $row;
        }
    }
}

// --- 4. PROCESAR POST (Ahora con datos disponibles) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['realizar_pedido'])) {
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

    header("Location: carrito.php");
    exit;
}

include './header.php'; 
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
    <fieldset style="width: 90%; max-width: 800px; margin: 20px auto; padding: 20px; border-radius: 10px;">
    <legend style="padding: 0 10px; font-weight: bold;"><?php echo ($idioma_actual=='espanol') ? "Tu Cesta de la Compra" : "Your Shopping Cart"; ?></legend>

    <div id="items-del-carrito">
        <?php 
        $hay_productos = false;
        $total_precio = 0;
        $cantidad_total = 0;

        foreach ($listaCarrito as $id_id => $cant):
            if (isset($productos_db[$id_id])):
                $hay_productos = true;
                $p = $productos_db[$id_id];
                $nombre = ($idioma_actual=='espanol') ? $p['nombre_es'] : $p['nombre_en'];
                $subtotal = $p['precio'] * $cant;
                $total_precio += $subtotal;
                $cantidad_total += $cant;
        ?>
        <div class="producto-carrito" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee;">
            <div style="display: flex; align-items: center;">
                <img src="<?php echo $p['imagen']; ?>" alt="img" style="width:60px; height:60px; object-fit:cover; margin-right:20px; border-radius:5px;">
                <div>
                    <h4 style="margin:0;"><?php echo $nombre; ?></h4>
                    <p style="margin:5px 0 0; color:#666;"><?php echo number_format($p['precio'],2); ?> €</p>
                    <div style="margin-top:5px; display:flex; align-items:center;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                            <button type="submit" name="cantidad_action" value="decrement" style="padding:2px 6px;">-</button>
                        </form>
                        <span style="margin:0 10px;"><?php echo $cant; ?></span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                            <button type="submit" name="cantidad_action" value="increment" style="padding:2px 6px;">+</button>
                        </form>
                        <span style="margin-left:15px;"><?php echo number_format($subtotal,2); ?> €</span>
                    </div>
                </div>
            </div>
            <form method="POST">
                <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                <button type="submit" name="remove_cart" style="background:#e74c3c; color:white; border:none; padding:8px 12px; cursor:pointer; border-radius:4px;">
                    <?php echo ($idioma_actual=='espanol') ? "Eliminar" : "Remove"; ?>
                </button>
            </form>
        </div>
        <?php 
            endif;
        endforeach;

        if (!$hay_productos): ?>
            <p style="text-align:center; padding:20px;"><?php echo ($idioma_actual=='espanol') ? "Tu carrito está vacío." : "Your cart is empty."; ?></p>
        <?php endif; ?>
    </div>

    <?php if ($hay_productos): ?>
    <div class="money" style="text-align:center; margin-top:20px;">
        <p><?php echo ($idioma_actual=='espanol') ? "Cantidad Total: " : "Total Quantity: "; ?><span id="contador-carrito"><?php echo $cantidad_total; ?></span></p>
        <p><?php echo ($idioma_actual=='espanol') ? "Precio Total: " : "Total Price: "; ?><span id="total-carrito"><?php echo number_format($total_precio,2); ?> €</span></p>
        <form method="POST" style="margin-top:10px;">
            <button type="submit" name="realizar_pedido" style="padding:10px 25px; border:none; border-radius:5px; background:#007bff; color:white; cursor:pointer;">
                <?php echo ($idioma_actual=='espanol') ? "Realizar Pedido" : "Place Order"; ?>
            </button>
        </form>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <form method="POST">
            <button type="submit" name="clear_cart" style="background:#555; color:white; padding:8px 20px; border:none; border-radius:5px; cursor:pointer;">
                <?php echo ($idioma_actual=='espanol') ? "Vaciar Carrito" : "Clear Cart"; ?>
            </button>
        </form>
    </div>
    <?php endif; ?>
    </fieldset>
</main>
</body>
</html>
