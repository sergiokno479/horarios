<?php
session_start();

require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    
    $sql = "SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        if (password_verify($contrasena, $fila['contrasena'])) {
            
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $fila['nombre'];

            header("Location: ../pages/main.php");
            exit();
        } else {
            echo "Contrasena incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../styles/crearyentrar.css">
</head>
<body>

<h1>Login</h1>

<form method="POST">
  <input type="email" name="correo" placeholder="Correo" required>
  <input type="password" name="contrasena" placeholder="Contrasena" required>
  <button type="submit">Entrar</button>
</form>

<a href="crear.php">¿No tienes cuenta? Regístrate aquí</a>

</body>
</html>
  