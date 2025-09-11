<?php

session_start();

require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre,correo, contrasena) VALUES ('$nombre','$correo','$contrasena')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['correo'] = $correo;
        header("Location: entrar.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="../styles/crearyentrar.css">
</head>
<body>

<h1>Registrarse</h1>

<form method="POST" >
  <input type="text" name="nombre" placeholder="Nombre" required>
  <input type="email" name="correo" placeholder="Correo" required>
  <input type="password" name="contrasena" placeholder="Contrasena" required>
  <button type="submit">Registrar</button>
</form>

<a href="entrar.php">¿Ya tienes cuenta? Inicia sesión</a>

</body>
</html>
