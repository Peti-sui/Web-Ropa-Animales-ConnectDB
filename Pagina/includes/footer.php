<?php

/* Inicializamos la variable $otra_lengua con el valor por defecto 'ingles'. Esto servirá
   como referencia para el idioma alternativo en caso de que no haya cookie definida. */
$otra_lengua = 'ingles';

/* Verificamos si existe la cookie 'idiomita' y si su valor es 'espanol'.
   - isset($_COOKIE['idiomita']) comprueba que la cookie exista para evitar errores.
   - $_COOKIE['idiomita'] == 'espanol' comprueba que el valor de la cookie indique español. */
if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    /* Si la cookie indica español, actualizamos $otra_lengua a 'espanol' y
       definimos $idioma_actual como 'espanol'. */
    $otra_lengua = 'espanol';
    $idioma_actual = 'espanol';
} else {
    /* Si la cookie no existe o no indica español, dejamos $idioma_actual como 'ingles'. */
    $idioma_actual = 'ingles';
}

/* Condicional para mostrar el footer según el idioma seleccionado.
   - Si $idioma_actual es 'espanol', se imprime el footer en español.
   - En caso contrario, se imprime en inglés. */
if ($idioma_actual == 'espanol') {
    echo '<footer>
        <p style="color: white;">Creado por Rau00 y Negro (Scoo)</p>
    </footer>';
} else {
    echo '<footer>
        <p style="color: white;">Created by Rau00 and Dark (Scoo)</p>
    </footer>';
}

?>
