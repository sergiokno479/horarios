<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['preferencias'])) {
    $_SESSION['preferencia'] = $_POST['preferencias'];
    require_once 'procesar.php';
    header("Location: opciones.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Preferencias de Horario</title>
  <script src="../scripts/pregunta.js"></script>
  <link rel="stylesheet" href="../styles/pregunta.css">
  
</head>
  <h1>Preferencias de Horario</h1>
  <form method="post" action="">
    <div class="form-title">¿Qué quieres priorizar?</div>

    <div class="checkbox-table">
  <?php
      $opciones = [
            "Menos horas en la U" => "- Útil si quieres salir rápido del campus.",
            "Muchas horas en la U" => "- Ideal si estudias o convives en la U.",
            "Pocos días en la U" => "- Bueno si prefieres más días totalmente libres.",
            "Muchos días en la U" => "- Recomendado para jornadas más ligeras.",
            "Pocos huecos" => "- Elige si odias esperar entre clases.",
            "Pocos huecos pero grandes" => "- Bien si aprovechas huecos para estudiar o comer.",
            "Muchos huecos" => "- Útil si necesitas pausas frecuentes.",
            "Días intensos, más libres" => "- Carga todo en pocos días y libera otros.",
            "Horario balanceado" => "- Para quienes buscan equilibrio y estabilidad.",
            "Tiempos óptimos de estudio" => "- Ideal si estudias entre clases."
        ];


        $i = 1;
        foreach ($opciones as $titulo => $descripcion) {
          echo '
          <div class="checkbox-item" data-value="'.$i.'">
            <input type="radio" name="preferencias" value="'.$i.'" id="opt'.$i.'">
            <label for="opt'.$i.'">'.$titulo.'</label>
            <small>'.$descripcion.'</small>
          </div>';
          $i++;
        }
  ?>
</div>
    <button type="submit">Enviar</button>
  </form>


<script>  
    document.querySelectorAll('.checkbox-item').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.checkbox-item').forEach(el => el.classList.remove('selected'));
        item.classList.add('selected');
        item.querySelector('input[type="radio"]').checked = true;
      });
    });
</script>

</body>
</html>
