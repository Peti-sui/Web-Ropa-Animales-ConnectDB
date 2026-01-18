<?php
/* Verifica si la solicitud HTTP es de tipo POST, lo que indica que el usuario ha enviado un formulario */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* Comprueba si se ha enviado el formulario de "aplicar ajustes" */
    if (isset($_POST['aplicar_ajustes'])) {
        /* Obtiene el tema seleccionado por el usuario o asigna 'claro' por defecto si no se seleccionó ninguno */
        $nuevo_tema = $_POST['tema_seleccionado'] ?? 'claro';
        /* Obtiene el idioma seleccionado por el usuario o asigna 'espanol' por defecto si no se seleccionó ninguno */
        $nuevo_idioma = $_POST['idioma_seleccionado'] ?? 'espanol';

        /* Establece cookies para guardar el tema y el idioma durante 48 horas (60 seg * 60 min * 48 horas) */
        setcookie('temita', $nuevo_tema, time() + (60 * 60 * 48));
        setcookie('idiomita', $nuevo_idioma, time() + (60 * 60 * 48));

    /* Comprueba si se ha enviado el formulario de "resetear ajustes" */
    } elseif (isset($_POST['resetear_ajustes'])) {
        /* Elimina las cookies actuales expirándolas */
        setcookie('temita', '', time() - 3600);
        setcookie('idiomita', '', time() - 3600);
        /* Vuelve a establecer las cookies con los valores por defecto 'claro' y 'espanol' */
        setcookie('temita', 'claro', time() + (60 * 60 * 48));
        setcookie('idiomita', 'espanol', time() + (60 * 60 * 48));
    }

    /* Redirige al usuario nuevamente a la página de preferencias después de procesar los formularios */
    header('Location: preferencias.php');
    exit();
}

/* Obtiene el tema actual de la cookie 'temita' o asigna 'claro' si no existe */
$tema_actual = $_COOKIE['temita'] ?? 'claro';
/* Obtiene el idioma actual de la cookie 'idiomita' o asigna 'espanol' si no existe */
$idioma_actual = $_COOKIE['idiomita'] ?? 'espanol';

/* Define una clase CSS según el tema actual; si es 'oscuro', asigna 'oscuro', si no, cadena vacía */
$tema_class = ($tema_actual === 'oscuro') ? 'oscuro' : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluye los estilos específicos para la página de ajustes -->
    <link rel="stylesheet" href="./src/styles/styleSettings.css">
    <!-- Incluye los estilos generales de la aplicación -->
    <link rel="stylesheet" href="./src/styles/style.css">
</head>
<body>
    <!-- Incluye el encabezado de la página -->
    <?php include_once './includes/header.php' ?>
    
    <fieldset>
        <!-- Muestra el título del formulario según el idioma seleccionado -->
        <legend><?php echo ($idioma == 'espanol') ? "Ajustes" : "Settings"; ?></legend>
        
        <!-- Formulario para aplicar los ajustes de idioma y tema -->
        <form method="POST" action="preferencias.php" style="width:300px;">
            <div class="form-group">
                <!-- Etiqueta para seleccionar idioma, cambia según idioma actual -->
                <label for="idioma_seleccionado"><?php echo ($idioma == 'espanol') ? "Selecciona el Idioma: " : "Select the language: "; ?></label>
                <select name="idioma_seleccionado" id="idioma_seleccionado">
                    <!-- Opción Español, marcada como seleccionada si es el idioma actual -->
                    <option value="espanol" <?php if ($idioma_actual === 'espanol') echo 'selected'; ?>><?php echo ($idioma == 'espanol') ? "Español" : "Spanish"; ?></option>
                    <!-- Opción Inglés, marcada como seleccionada si es el idioma actual -->
                    <option value="ingles" <?php if ($idioma_actual === 'ingles') echo 'selected'; ?>><?php echo ($idioma == 'espanol') ? "Inglés" : "English"; ?></option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <!-- Selector de tema, no cambia según idioma -->
                <label for="idioma_seleccionado"><?php echo ($idioma == 'espanol') ? "Selecciona el Tema: " : "Select theme: "; ?></label>
                <select name="tema_seleccionado" id="tema_seleccionado">
                    <!-- Opción Claro, marcada como seleccionada si es el tema actual -->
                    <option value="claro" <?php if ($tema_actual === 'claro') echo 'selected';?>><?php echo ($idioma == 'espanol') ? "Claro" : "Light"; ?></option>
                    <!-- Opción Oscuro, marcada como seleccionada si es el tema actual -->
                    <option value="oscuro" <?php if ($tema_actual === 'oscuro') echo 'selected';?>><?php echo ($idioma == 'espanol') ? "Oscuro" : "Dark"; ?></option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <!-- Botón de envío para aplicar ajustes, cambia texto según idioma -->
                <input type="submit" name="aplicar_ajustes" <?php if ($idioma == 'espanol') {
                    echo 'value="Aplicar"';
                } else {
                    echo 'value="Apply"';
                }?>>
            </div>
        </form>

        <!-- Formulario separado para resetear los ajustes a valores por defecto -->
        <form method="POST" action="preferencias.php" style="width:300px;">
             <div class="form-group">
                <!-- Botón de envío para resetear cambios, cambia texto según idioma -->
                <input type="submit" name="resetear_ajustes" <?php if ($idioma == 'espanol') {
                    echo 'value="Resetear Cambios"';
                } else {
                    echo 'value="Reset changes"';
                }?>>
            </div>
        </form>

    </fieldset>

    <!-- Incluye el pie de página de la página -->
    <?php include_once './includes/footer.php' ?>
</body>
</html>
