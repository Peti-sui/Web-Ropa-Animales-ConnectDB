<?php

$otra_lengua = 'ingles';

if (isset($_COOKIE['idiomita']) && $_COOKIE['idiomita'] == 'espanol') {
    $otra_lengua = 'espanol';
    $idioma_actual = 'espanol';
} else {
    $idioma_actual = 'ingles';
}

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