<?php

/* Inicializamos un array para almacenar posibles errores durante el login */
$errores = [];

/* Verificamos si la sesión no está activa, en tal caso la iniciamos */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Recuperamos el idioma desde la cookie 'idiomita', si no existe, se usa 'espanol' por defecto */
$idioma = $_COOKIE['idiomita'] ?? 'espanol';

/* Comprobamos si el formulario ha sido enviado mediante POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Obtenemos los valores enviados por el formulario, usando valores vacíos como fallback */
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['password'] ?? '';

    /* Validamos que el campo usuario no esté vacío */
    if (empty($usuario)) {
        /* Añadimos un mensaje de error en el idioma correspondiente */
        $errores[] = ($idioma == 'espanol') ? "El campo usuario no puede estar vacío" : "The user field cannot be empty";
    }

    /* Validamos que el campo contraseña no esté vacío */
    if (empty($contrasena)) {
        /* Añadimos un mensaje de error en el idioma correspondiente */
        $errores[] = ($idioma == 'espanol') ? "El campo contraseña no puede estar vacío" : "The password field cannot be empty";
    }

    /* Si no hay errores de validación, procedemos a verificar las credenciales */
    if (empty($errores)) {
        /* Caso de usuario admin con contraseña '1234' */
        if ($usuario === 'admin' && $contrasena === '1234') {
            /* Asignamos el rol 'admin' en la sesión */
            $_SESSION['usuario_rol'] = 'admin'; 
            /* Redirigimos a la página principal */
            header('Location: ./principal.php');
            exit();
        } else {
            /* Para cualquier otro usuario se asigna rol 'normal' */
            $_SESSION['usuario_rol'] = 'normal';
            /* Redirigimos a la página principal */
            header('Location: ./principal.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace al archivo de estilos CSS para el formulario de login -->
    <link rel="stylesheet" href="./src/styles/style-login.css">
    <title>Iniciar Sesión</title>

</head>
<body>
<!-- Formulario de inicio de sesión que envía datos a la misma página mediante POST -->
<form method="POST" action="login.php">
    <fieldset>
        <!-- Leyenda del formulario que cambia según el idioma -->
        <legend><?php echo ($idioma == 'espanol') ? "Inicio de Sesión" : "Login: "; ?></legend>

        <div class="form-group">
            <!-- Etiqueta y campo para el nombre de usuario -->
            <label for="usuario"><?php echo ($idioma == 'espanol') ? "Usuario: " : "User: "; ?></label>
            <input type="text" name="usuario" id="usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <!-- Etiqueta y campo para la contraseña -->
            <label for="password"><?php echo ($idioma == 'espanol') ? "Contraseña: " : "Password: "; ?></label>
            <input type="password" name="password" id="password">
        </div>
        
        <div class="form-group">
            <!-- Botón de envío del formulario -->
            <input type="submit" value="Acceder">
        </div>

        <!-- Si existen errores, los mostramos dentro de una lista -->
        <?php if (!empty($errores)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </fieldset>
</form>


</body>
</html>
