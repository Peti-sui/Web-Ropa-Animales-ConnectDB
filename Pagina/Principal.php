<?php
include './header.php';

$cookie_name = 'listaDeseados';

$otra_lengua = 'ingles';

if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    $otra_lengua = 'espanol';
    $idioma_actual = 'espanol';
} else {
    $idioma_actual = 'ingles';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body>
    <main>
        <div class="contenido">

            <?php foreach ($productos as $id => $producto): 
                $nombreProducto = $producto['nombre'][$idioma_actual];
            ?>

            <div class="producto" 
                data-id="<?php echo $id; ?>" 
                data-name="<?php echo $nombreProducto; ?>" 
                data-price="<?php echo $producto['precio']; ?>">

                <div class="product-image-container">
                    <img src="<?php echo ($producto['imagen'] ?? 'img/default.jpg'); ?>"alt="<?php echo $nombreProducto; ?>">
                </div>

                <h3><?php echo $nombreProducto; ?></h3>


                <div class="abajo">

                    <p><strong>
                        <?php echo ($idioma_actual == 'espanol') ? "Precio:" : "Price:"; ?>
                        <?php echo number_format($producto['precio'], 2); ?> €
                    </strong></p>

                    <button class="agregar-al-carrito">
                        <?php 
                        echo ($idioma_actual == 'espanol') ? 
                             "Añadir al carrito" : 
                             "Add to cart"; 
                        ?>
                    </button>

                    <form method="POST" action="">
                        <input type="hidden" name="producto_id" value="<?php echo $id; ?>">
                        <button type="submit" name="añadir_producto_lista">
                            <?php 
                            echo ($idioma_actual == 'espanol') ? 
                                 "Añadir a Deseados" : 
                                 "Add to Wishlist"; 
                            ?>
                        </button>
                    </form>
                </div>

            </div>

            <?php endforeach; ?>
        </div>

        <p style="text-align: center;">
        <?php 
            echo ($idioma_actual == 'espanol') 
                ? "Página en construcción."
                : "Under construction.";
            ?>
            
            <img style="width: 100px; display: block; margin: 10px auto;" src="https://i.pinimg.com/originals/8e/9d/47/8e9d4763350b27ca7ef4d32921528470.gif" alt="zi">
        </p>

    </main>
</body>
</html>

<?php include './footer.php'; ?>
