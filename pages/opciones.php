<?php
session_start();
// Guardar el horario seleccionado si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seleccionado'])) {
    $horarios = $_SESSION['resfinal'];
    $id_horario = intval($_POST['seleccionado']);
    $usuario_id = $_SESSION['usuario_id'] ?? 0;

    if (isset($horarios[$id_horario])) {
        $horario = $horarios[$id_horario]['clases'];

        require_once '../conexion.php';

        // Eliminar el horario anterior del usuario (si existe)
        $stmt_delete = $conn->prepare("DELETE FROM h_guardados WHERE id_usuario = ?");
        $stmt_delete->bind_param("i", $usuario_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Insertar cada materia del nuevo horario

        print_r($horario);

        foreach ($horario as $materia) {
            $nombre = $materia['materia'] ?? '';
            $dia = $materia['dia'] ?? '';
            $entrada = $materia['inicio'] ?? '';
            $salida = $materia['fin'] ?? '';

            $stmt = $conn->prepare("INSERT INTO h_guardados (id_usuario, nombre_materia, dia, hora_entrada, hora_salida) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $usuario_id, $nombre, $dia, $entrada, $salida);
            $stmt->execute();
            $stmt->close();
        }

        $conn->close();
        header("Location: gracias.php");
        exit();

    } else {
        echo "<script>alert('Horario inválido');</script>";
    }
}

$datos = $_SESSION['resfinal'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="../scripts/opciones.js"></script>
  <link rel="stylesheet" href="../styles/opciones.css">
  <title>Slider de Horarios</title>

</head>
<body>
<div class = "ss"><h1>Preferencias de Horarios!</h1></div>
<div class="slider-wrapper">
  <button class="arrow left" onclick="moveSlide(-1)">&#10094;</button>
  <div class="slider-container">
    <div class="slider" id="slider">
      <?php foreach ($datos as $index => $horario): ?>
        <div class="slide">
          <div class="card">
            <div class="iframe-container">
              <iframe src="http://localhost:3000/pages/TEST.php?id=<?= $index ?>"></iframe>
            </div>
          </div>
          <div class="button-container">
            <form method="post">
              <input type="hidden" name="seleccionado" value="<?= $index ?>">
              <button type="submit">Este</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <button class="arrow right" onclick="moveSlide(1)">&#10095;</button>
</div>

<script>
  let currentIndex = 0;
  function moveSlide(direction) {
    const slider = document.getElementById('slider');
    const totalSlides = slider.children.length;
    currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
  }
</script>
</body>
</html>
