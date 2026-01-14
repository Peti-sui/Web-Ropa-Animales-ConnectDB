<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styleSettings.css">
    <link rel="stylesheet" href="./css/carrito.css">
    <title>TuTiendaDeConfi</title>
</head>
<body>
    <?php 
        include './header.php'; 
    ?>

    <fieldset>
        <legend>
            <?php echo ($idioma == 'espanol') ? "Tu cesta de la compra" : "Your shopping cart"; ?>
        </legend>

        <div class="cajita">
            <div id="items-del-carrito">
            </div>
            <div class="money">
                <p><?php echo ($idioma == 'espanol') ? "Cantidad Total: " : "Total Quantity: "; ?><span id="contador-carrito">0</span></p>
                <p><?php echo ($idioma == 'espanol') ? "Precio Total: " : "Total Price: "; ?><span id="total-carrito">0.00€</span></p>
            </div>
            <button class="pagar"><?php echo ($idioma == 'espanol') ? "Realizar Pedido" : "Place order"; ?></button>
        </div>

    </fieldset>

    <?php 
        include './footer.php'; 
    ?>
</body>
</html>
