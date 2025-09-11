<?php
session_start();
require_once '../conexion.php';
$id_usuario = $_SESSION['usuario_id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ACTUALIZAR PERFIL
    if (isset($_POST['actualizar'])) {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $password_actual = $_POST['password_actual'];
        $password_nueva = $_POST['password_nueva'];


        // Verificar contraseña actual
        $sql = "SELECT contraseña FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($hash_guardado);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password_actual, $hash_guardado)) {
            // Contraseña verificada, proceder a actualizar
            $nuevo_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nombre, $correo, $nuevo_hash, $id_usuario);
            if ($stmt->execute()) {
                $_SESSION['nombre'] = $nombre;
                $_SESSION['correo'] = $correo;
                $mensaje = "Información actualizada correctamente.";
            } else {
                $mensaje = "Error al actualizar.";
            }
            $stmt->close();
        } else {
            $mensaje = "La contraseña actual no es correcta.";
        }

    // ELIMINAR PERFIL
    } elseif (isset($_POST['eliminar'])) {
        // Primero eliminamos horarios del usuario
        $conn->query("DELETE FROM h_guardados WHERE id_usuario = $id_usuario");

        // Luego eliminamos el usuario
        $conn->query("DELETE FROM usuarios WHERE id = $id_usuario");

        session_destroy();
        header("Location: ../index.html");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel de Usuario</title>
  <link rel="stylesheet" href="../styles/perfil.css">
</head>
<body>

  <div class="header">
     <a href = "../pages/main.php"><div style="font-size: 1.5em; font-weight: bold;">UniTime</div></a>
     <a href = "perfil.php"><div style="font-size: 1.1em; font-weight: bold;"><?="Bienvenido ".$_SESSION['nombre']?></div></a>
  </div>

  <div class="container">
    <div class="sidebar">
      <a href="../pages/agghor.php">Crear Horario Nuevo</a>
      <a href="perfil.php">Mi Perfil</a>
      <a href="../authen/logout.php">Cerrar Sesión</a>  
    </div>

    <div class="main">
        
        <div class="headerb">
        <div><?="Editar perfil de ".$_SESSION['nombre'];?></div>
        </div>

        <div class="main">
        <div class="formulario">
            <form method="POST">
                <label>Nombre actual : </label><?=$_SESSION['nombre'];?><br><br>
                <label>Ingrese su nuevo nombre : </label><input type="text" name="nombre"><br><br>
                
                <label>Correo actual : </label><?=$_SESSION['correo'];?><br><br>
                <label>Ingrese su nuevo correo : </label><input type="email" name="correo"><br><br>

                <label>Ingrese su contraseña actual: </label> <input type="password" name="password_actual"><br><br>
                <label>* Si su contraseña actual es correcta esta sera modificada por la nueva *</label><br><br>
                <label>Ingrese su nueva contraseña : </label>
                <input type="password" name="password_nueva" required><br><br><br>

                <button type="submit" name="actualizar" class="btn btn-azul">Actualizar</button>
                <button type="submit" name="eliminar" class="btn btn-rojo" onclick="return confirm('¿Estás seguro de eliminar tu cuenta?')">Eliminar cuenta</button>
            </form>
           
        </div>
        </div>


    </div>

</body>
</html>
