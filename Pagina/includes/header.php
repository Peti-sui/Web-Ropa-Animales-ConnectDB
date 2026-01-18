<?php
/* Verifica si la sesión de PHP ya está activa. 
   Si no lo está, la inicia para poder usar variables de sesión. */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Obtiene el rol del usuario desde la sesión. 
   Si no existe, se asigna el rol por defecto "invitado". */
$rol_usuario = $_SESSION['usuario_rol'] ?? 'invitado';

/* Determina si el usuario está registrado comparando su rol con "invitado". */
$usuario_registrado = ($rol_usuario !== 'invitado');

/* Redirige al usuario a la página principal si se envía un formulario POST
   y el usuario no está autenticado (no registrado). */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$usuario_registrado) {
        header('Location: ./principal.php');
        exit();
    }
}

/* Obtiene las preferencias de idioma y tema desde cookies. 
   Si no existen, se asignan valores predeterminados ("espanol" y "claro"). */
$idioma = $_COOKIE['idiomita'] ?? 'espanol';
$tema = $_COOKIE['temita'] ?? 'claro';

/* Define los textos del menú en español e inglés, incluyendo la ruta de la imagen de configuración. */
$menu_texts = [
    'espanol' => ['Inicio', 'Lista Deseados', 'Carrito', 'Login', 'Logout', 'Insertar productos', 'Eliminar Productos','./src/images/others/config.png'],
    'ingles'  => ['Home', 'Wishlist', 'Cart', 'Login', 'Logout', 'Insert Products', 'Delete Products','./src/images/others/config.png']
];

/* Selecciona los textos del menú según el idioma almacenado en la cookie. 
   Si el idioma no existe, se usan los textos en español por defecto. */
$text = $menu_texts[$idioma] ?? $menu_texts['espanol'];

/* Determina la clase CSS a aplicar al <html> según el tema (oscuro o claro). */
$tema_class = ($tema === 'oscuro') ? 'oscuro' : '';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma_actual; ?>" class="<?php echo $tema_class; ?>">
<head>
    <meta charset="UTF-8">
    <!-- Icono del sitio -->
    <link
      rel="icon"
      type="image/png"
      href="./src/images/others/favicon-32x32.png"
    />
    <title>
        <?php 
        /* Cambia el título de la página según el idioma seleccionado. */
        if($idioma == 'espanol') {
            echo "Patitas Alegres";
        } else {
            echo "Happy Paws";
        }
        ?>
    </title>
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body> 
<header>
    <div style="margin-left: 2%;">
        <?php 
        /* Mensaje de bienvenida según el rol del usuario y el idioma. */
        if ($rol_usuario === 'admin'):
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
            <!-- Botones principales del menú -->
            <li><a href="./principal.php"><button><?php echo $text[0]; ?></button></a></li>
            <li><a href="./listaDeseados.php"><button><?php echo $text[1]; ?></button></a></li>
            <li><a href="./carrito.php"><button><?php echo $text[2]; ?></button></a></li>
            
            <?php if ($rol_usuario === 'admin'): ?>
                <!-- Opciones exclusivas para administrador -->
                <li><a href="./guardar.php"><button><?php echo $text[5]; ?></button></a></li>
                <li><a href="./eliminar.php"><button><?php echo $text[6]; ?></button></a></li>
            <?php endif; ?>

            <?php if (!$usuario_registrado):?>
                <!-- Si el usuario no está registrado, muestra Login -->
                <li><a href="./login.php"><button><?php echo $text[3]; ?></button></a></li>
            <?php else:?>
                <!-- Si el usuario está registrado, muestra Logout -->
                <li><a href="./actions/logout.php"><button><?php echo $text[4]; ?></button></a></li>
            <?php endif; ?>
            
            <!-- Botón de configuración con icono -->
            <li>
                <a href="./preferencias.php"> 
                    <button class="image">
                        <img src="<?php echo $text[7]; ?>" alt="Settings">
                    </button>
                </a>
            </li>
        </ul>
    </menu>
</header>

<main>
