<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['aplicar_ajustes'])) {
        $nuevo_tema = $_POST['tema_seleccionado'] ?? 'claro';
        $nuevo_idioma = $_POST['idioma_seleccionado'] ?? 'espanol';

        setcookie('temita', $nuevo_tema, time() + (60 * 60 * 48));
        setcookie('idiomita', $nuevo_idioma, time() + (60 * 60 * 48));

    } elseif (isset($_POST['resetear_ajustes'])) {
        setcookie('temita', '', time() - 3600);
        setcookie('idiomita', '', time() - 3600);
        setcookie('temita', 'claro', time() + (60 * 60 * 48));
        setcookie('idiomita', 'espanol', time() + (60 * 60 * 48));
    }

    header('Location: preferencias.php');
    exit();
}


$tema_actual = $_COOKIE['temita'] ?? 'claro';
$idioma_actual = $_COOKIE['idiomita'] ?? 'espanol';


$tema_class = ($tema_actual === 'oscuro') ? 'oscuro' : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/styles/styleSettings.css">
    <link rel="stylesheet" href="./src/styles/style.css">
    <title>TuTiendaDeConfi</title>
</head>
<body>
    <?php include_once './header.php' ?>
    <fieldset>
        <legend><?php echo ($idioma == 'espanol') ? "Ajustes" : "Settings"; ?></legend>
        
        <form method="POST" action="preferencias.php" style="width:300px;">
            <div class="form-group">
                <label for="idioma_seleccionado"><?php echo ($idioma == 'espanol') ? "Selecciona el Idioma: " : "Select the language: "; ?></label>
                <select name="idioma_seleccionado" id="idioma_seleccionado">
                    <option value="espanol" <?php if ($idioma_actual === 'espanol') echo 'selected'; ?>><?php echo ($idioma == 'espanol') ? "Español" : "Spanish"; ?></option>
                    <option value="ingles" <?php if ($idioma_actual === 'ingles') echo 'selected'; ?>><?php echo ($idioma == 'espanol') ? "Inglés" : "English"; ?></option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <label for="tema_seleccionado">Selecciona el Tema:</label>
                <select name="tema_seleccionado" id="tema_seleccionado">
                    <option value="claro" <?php if ($tema_actual === 'claro') echo 'selected';?>><?php echo ($idioma == 'espanol') ? "Claro" : "Light"; ?></option>
                    <option value="oscuro" <?php if ($tema_actual === 'oscuro') echo 'selected';?>><?php echo ($idioma == 'espanol') ? "Oscuro" : "Dark"; ?></option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" name="aplicar_ajustes" <?php if ($idioma == 'espanol') {
                    echo 'value="Aplicar"';
                } else {
                    echo 'value="Apply"';
                }?>>
            </div>
        </form>

        <form method="POST" action="preferencias.php" style="width:300px;">
             <div class="form-group">
                <input type="submit" name="resetear_ajustes" <?php if ($idioma == 'espanol') {
                    echo 'value="Resetear Cambios"';
                } else {
                    echo 'value="Reset changes"';
                }?>>
            </div>
        </form>

    </fieldset>
    <?php include_once './footer.php' ?>
</body>
</html>
