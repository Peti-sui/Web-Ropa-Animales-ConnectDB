<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "Ropales";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
