<?php
require "conexion.php";

// Gestión de Idioma
$idioma_actual = (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') ? 'espanol' : 'ingles';

// Funciones de Cookie
function recibirCookieDeseados() {
    if (isset($_COOKIE['listaDeseados'])) {
        $list = json_decode($_COOKIE['listaDeseados'], true);
        return is_array($list) ? $list : [];
    }
    return [];
}

function guardarCookieDeseados($list) {
    setcookie('listaDeseados', json_encode(array_values($list)), time() + (86400 * 30), "/");
}

$listaDeseados = recibirCookieDeseados();

// --- PROCESAR POST (Añadir, Eliminar, Vaciar) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // AÑADIR (Viene de principal.php)
    if (isset($_POST['añadir_producto_lista'], $_POST['producto_id'])) {
        $id_nuevo = (int)$_POST['producto_id'];
        if (!in_array($id_nuevo, $listaDeseados)) {
            $listaDeseados[] = $id_nuevo;
            guardarCookieDeseados($listaDeseados);
        }
    }

    // ELIMINAR UNO
    if (isset($_POST['remove_wish'], $_POST['producto_id'])) {
        $id_eliminar = (int)$_POST['producto_id'];
        $posicion = array_search($id_eliminar, $listaDeseados);
        if ($posicion !== false) {
            unset($listaDeseados[$posicion]);
            guardarCookieDeseados($listaDeseados);
        }
    }

    // VACIAR TODO
    if (isset($_POST['clear_list'])) {
        $listaDeseados = [];
        guardarCookieDeseados([]);
    }

    // Redirigir siempre después de un POST para que la cookie se actualice en el navegador
    header("Location: listaDeseados.php");
    exit;
}

// 2. OBTENER PRODUCTOS DE LA BASE DE DATOS
$productos_db = [];
if (!empty($listaDeseados)) {
    $ids_limpios = implode(',', array_map('intval', $listaDeseados));
    $sql = "SELECT * FROM productos WHERE id IN ($ids_limpios)";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos_db[$row['id']] = $row;
        }
    }
}

include './header.php'; 
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
    <fieldset style="width: 90%; max-width: 800px; margin: 20px auto; padding: 20px; border-radius: 10px;">
        <legend style="padding: 0 10px; font-weight: bold;">
            <?php echo ($idioma_actual == 'espanol') ? "Mi Lista de Deseados" : "My Wishlist"; ?>
        </legend>

        <div class="wishlist-items">
            <?php 
            $hay_productos = false;
            foreach ($listaDeseados as $id_id):
                if (isset($productos_db[$id_id])):
                    $hay_productos = true;
                    $p = $productos_db[$id_id];
                    $nombre = ($idioma_actual == 'espanol') ? $p['nombre_es'] : $p['nombre_en'];
            ?>
                <div class="producto-lista" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: center;">
                        <img src="<?php echo $p['imagen']; ?>" alt="img" style="width: 60px; height: 60px; object-fit: cover; margin-right: 20px; border-radius: 5px;">
                        <div>
                            <h4 style="margin: 0;"><?php echo $nombre; ?></h4>
                            <p style="margin: 5px 0 0; color: #666;"><?php echo number_format($p['precio'], 2); ?> €</p>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $id_id; ?>">
                        <button type="submit" name="remove_wish" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px;">
                            <?php echo ($idioma_actual == 'espanol') ? "Eliminar" : "Remove"; ?>
                        </button>
                    </form>
                </div>
            <?php 
                endif;
            endforeach; 

            if (!$hay_productos): ?>
                <p style="text-align: center; padding: 20px;">
                    <?php echo ($idioma_actual == 'espanol') ? "No tienes productos en tu lista." : "Your wishlist is empty."; ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if ($hay_productos): ?>
        <div style="text-align: center; margin-top: 30px;">
            <form method="POST">
                <button type="submit" name="clear_list" style="background: #333; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer;">
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
<?php include './footer.php'; ?>
</body>
</html>
