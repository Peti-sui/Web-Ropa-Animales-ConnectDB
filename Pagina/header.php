<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// include_once './productos/productos.php';

// Obtiene el rol del usuario desde la sesión o asigna "invitado" si no existe.
$rol_usuario = $_SESSION['usuario_rol'] ?? 'invitado';

// Determina si el usuario está registrado comparando su rol con el valor por defecto.
$usuario_registrado = ($rol_usuario !== 'invitado');

// Redirige a login si se envía un formulario POST y el usuario no está autenticado.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$usuario_registrado) {
        header('Location: ./login.php');
        exit();
    }
}

// Obtiene idioma y tema desde cookies o asigna valores predeterminados si no existen.
$idioma = $_COOKIE['idiomita'] ?? 'espanol';
$tema = $_COOKIE['temita'] ?? 'claro';

// Define los textos del menú en ambos idiomas con sus rutas correspondientes.
$menu_texts = [
    'espanol' => ['Inicio', 'Lista Deseados', 'Carrito', 'Login', 'Logout', './src/images/others/config.png'],
    'ingles'  => ['Home', 'Wishlist', 'Cart', 'Login', 'Logout', './src/images/others/config.png']
];

// Selecciona los textos del menú según el idioma almacenado en la cookie.
$text = $menu_texts[$idioma] ?? $menu_texts['espanol'];

// Determina la clase CSS a aplicar según el tema claro u oscuro.
$tema_class = ($tema === 'oscuro') ? 'oscuro' : '';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma_actual; ?>" class="<?php echo $tema_class; ?>">
<head>
    <meta charset="UTF-8">
    <title>TuTiendaDeConfi</title>
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body> 
<header>
    <div style="margin-left: 2%;">
        <?php if ($rol_usuario === 'admin'):
            if ($idioma == 'espanol') { 
                echo "<p style='margin-right: 10px;'>¡Bienvenido Administrador!</p>";
            } else {
                echo "<p style='margin-right: 10px;'>Welcome Administrator!</p>";
            }?>
        <?php elseif ($rol_usuario === 'normal'):
            if ($idioma == 'espanol') { 
                echo "<p>¡Bienvenido Usuario!</p>";
            } else {
                echo "<p>Welcome User!</p>";
            }?>
        <?php endif; ?>
    </div>

    <menu>
        <ul>
            <li><a href="./index.php"><button><?php echo $text[0]; ?></button></a></li>
            <li><a href="./listaDeseados.php"><button><?php echo $text[1]; ?></button></a></li>
            <li><a href="./carrito.php"><button><?php echo $text[2]; ?></button></a></li>
            
            <?php if (!$usuario_registrado):?>
                <li><a href="./login.php"><button><?php echo $text[3]; ?></button></a></li>
            <?php else:?>
                <li><a href="./logout.php"><button><?php echo $text[4]; ?></button></a></li>
            <?php endif; ?>
            
            <li>
                <a href="./preferencias.php"> 
                    <button class="image">
                        <img src="<?php echo $text[5]; ?>" alt="Settings">
                    </button>
                </a>
            </li>
        </ul>
    </menu>
</header>

<main>
