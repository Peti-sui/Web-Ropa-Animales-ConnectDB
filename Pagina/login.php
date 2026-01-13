<?php

$errores = [];

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$idioma = $_COOKIE['idiomita'] ?? 'espanol';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['password'] ?? '';

    if (empty($usuario)) {
        $errores[] = ($idioma == 'espanol') ? "El campo usuario no puede estar vacío" : "The user field cannot be empty";
    }

    if (empty($contrasena)) {
        $errores[] = ($idioma == 'espanol') ? "El campo contraseña no puede estar vacío" : "The password field cannot be empty";
    }

    if (empty($errores)) {
        if ($usuario === 'admin' && $contrasena === '1234') {
            $_SESSION['usuario_rol'] = 'admin'; 
            header('Location: ./Principal.php');
            exit();
        } else {
            $_SESSION['usuario_rol'] = 'normal';
            header('Location: ./Principal.php');
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
    <link rel="stylesheet" href="./src/styles/style-login.css">
    <title>Iniciar Sesión</title>

</head>
<body>
<form method="POST" action="login.php">
    <fieldset>
        <legend><?php echo ($idioma == 'espanol') ? "Inicio de Sesión" : "Login: "; ?></legend>

        <div class="form-group">
            <label for="usuario"><?php echo ($idioma == 'espanol') ? "Usuario: " : "User: "; ?></label>
            <input type="text" name="usuario" id="usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="password"><?php echo ($idioma == 'espanol') ? "Contraseña: " : "Password: "; ?></label>
            <input type="password" name="password" id="password">
        </div>
        
        <div class="form-group">
            <input type="submit" value="Acceder">
        </div>

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
