<?php

// Lógica de idioma consistente con tu segundo archivo
if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    $idioma_actual = 'espanol';
} else {
    $idioma_actual = 'ingles';
}

// Funciones para gestionar la cookie
function recibirCookieDeseados() {
    if (isset($_COOKIE['listaDeseados'])) {
        $list = json_decode($_COOKIE['listaDeseados'], true);
        return is_array($list) ? $list : [];
    }
    return [];
}

function guardarCookieDeseados($list) {
    $cookie_value = json_encode(array_values($list)); // array_values para reindexar
    setcookie('listaDeseados', $cookie_value, time() + 60*60*48, "/"); // "/" para que sea accesible en toda la web
}

$listaDeseados = recibirCookieDeseados();

// Lógica para AÑADIR (por si vienes del otro PHP mediante POST)
if (isset($_POST['añadir_producto_lista'], $_POST['producto_id'])) {
    $id_nuevo = (int)$_POST['producto_id'];
    if (!in_array($id_nuevo, $listaDeseados)) {
        $listaDeseados[] = $id_nuevo;
        guardarCookieDeseados($listaDeseados);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Eliminar un producto
if (isset($_POST['remove_wish'], $_POST['producto_id'])) {
    $id_a_eliminar = (int)$_POST['producto_id'];
    $clave = array_search($id_a_eliminar, $listaDeseados);
    if ($clave !== false) {
        unset($listaDeseados[$clave]);
    }
    guardarCookieDeseados($listaDeseados);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Vaciar lista
if (isset($_POST['clear_list'])) {
    guardarCookieDeseados([]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ajustado a la ruta de CSS del segundo archivo -->
    <link rel="stylesheet" href="./src/styles/style.css">
    <title>TuTiendaDeConfi - Wishlist</title>
</head>
<body>
    <?php include './header.php';?>

    <main class="contenido">
        <fieldset style="width: 80%; margin: 20px auto; padding: 20px; border-radius: 8px;">
            <legend>
                <?php echo ($idioma_actual == 'espanol') ? "Mi Lista de Deseados" : "My Wishlist";?>
            </legend>
            
            <div class="wishlist-container">
                <?php 
                $vacia = true;
                if (!empty($listaDeseados)):
                    foreach ($listaDeseados as $producto_id):
                        // Verificamos que el producto exista en el array de datos
                        if (isset($productos[$producto_id])):
                            $vacia = false;
                            $item = $productos[$producto_id];
                            $nombre = $item['nombre'][$idioma_actual];
                            $precio = $item['precio'];
                ?>

                <div class="producto" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                    <div style="display: flex; align-items: center;">
                        <img src="<?php echo ($item['imagen'] ?? 'img/default.jpg'); ?>" alt="<?php echo $nombre; ?>" style="width: 50px; margin-right: 15px;">
                        <div>
                            <strong><?php echo $nombre; ?></strong><br>
                            <?php echo number_format($precio, 2); ?> €
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                        <button type="submit" name="remove_wish" style="background-color: #ff4d4d; color: white; border: none; padding: 5px 10px; cursor: pointer;">
                            <?php echo ($idioma_actual == 'espanol') ? "Eliminar" : "Remove"; ?> 
                        </button>
                    </form>
                </div>

                <?php
                        endif;
                    endforeach;
                endif;

                if ($vacia):
                    echo "<p>" . (($idioma_actual == 'espanol') ? "Tu lista está vacía." : "Your wishlist is empty.") . "</p>";
                endif;
                ?>
            </div>

            <?php if (!$vacia): ?>
            <form method="POST" style="text-align: center; margin-top: 20px;">
                <button type="submit" name="clear_list" class="deseados" style="padding: 10px 20px; cursor: pointer;">
                    <?php echo ($idioma_actual == 'espanol') ? "Vaciar lista completa" : "Clear full wishlist"; ?>
                </button>
            </form>
            <?php endif; ?>
        </fieldset>
    </main>

    <?php include './footer.php';?>
</body>
</html>
