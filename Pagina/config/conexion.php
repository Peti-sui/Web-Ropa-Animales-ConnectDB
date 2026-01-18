<?php
/* Definición de los parámetros de conexión a la base de datos */
$host = "localhost"; /* Dirección del servidor de base de datos, en este caso local */
$user = "root";      /* Usuario de la base de datos */
$password = "";      /* Contraseña del usuario de la base de datos */
$db = "Ropales";     /* Nombre de la base de datos a la que se desea conectar */

/* Creación de la conexión a la base de datos usando mysqli */
$conn = new mysqli($host, $user, $password, $db);

/* Verificación de si la conexión fue exitosa */
if ($conn->connect_error) {
    /* Si ocurre un error en la conexión, se detiene la ejecución y se muestra un mensaje */
    die("Error de conexión: " . $conn->connect_error);
}
?>
