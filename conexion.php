<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "wpage";

$conn = new mysqli($server, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
//require_once 'conexion.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

?>
