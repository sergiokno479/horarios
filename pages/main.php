<?php
session_start();


require_once '../conexion.php';

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT COUNT(*) AS total FROM h_guardados WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

$hay_horario = ($fila['total'] > 0);

if ($hay_horario) {
    $mensaje = "Tu Horario";
} else {
    $mensaje = "No tienes un horario guardado. ¡Crea uno!";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel de Usuario</title>
  <link rel="stylesheet" href="../styles/main.css">
</head>

<body>

  <div class="header">
     <a href = "main.php"><div style="font-size: 1.5em; font-weight: bold;">UniTime</div></a>
     <a href = "../authen/perfil.php"><div style="font-size: 1.1em; font-weight: bold;"><?="Bienvenido ".$_SESSION['nombre']?></div></a>
  </div>

  <div class="container">
    <div class="sidebar">
      <a href="agghor.php">Crear Horario Nuevo</a>
      <a href="../authen/perfil.php">Mi Perfil</a>
      <a href="../authen/logout.php">Cerrar Sesión</a>  
  </div>


    <div class="main">
     <div class="mensaje">
      <?php echo $mensaje; ?>

      <?php if (!$hay_horario): ?>
          <br><br>
          <a class="btn-crear" href="agghor.php">¡Crear mi mejor horario!</a>
      <?php else: ?>
          <div class="card">
              <div class="iframe-container" style="margin-top: 20px;">
                  <iframe src="http://localhost:3000/pages/TEST.php?id=999"></iframe>
                  
              </div>
          </div>
      <?php endif; ?>
    </div>


    </div>
  </div>

</body>
</html>
