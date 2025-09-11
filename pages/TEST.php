<?php
session_start();
require_once '../conexion.php';

// Obtener el ID del horario
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id !== 999) {
    
    $datf = $_SESSION['resfinal'];
    $todhor = [];
    foreach ($datf as $key) {
        $todhor[] = $key['clases'];
    }
}   

if ($id === 999) {
    if (!isset($_SESSION['usuario_id'])) {
        die("Usuario no autenticado.");
    }

    $id_usuario = $_SESSION['usuario_id'];

    $sql = "SELECT * FROM h_guardados WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $clases[] = [
            'materia' => $fila['nombre_materia'],
            'dia' => $fila['dia'],
            'inicio' => $fila['hora_entrada'],
            'fin' => $fila['hora_salida']
        ];
    }

    $stmt->close();
}else{
    // Obtener las clases del horario seleccionado
    $clases = isset($todhor[$id]) ? $todhor[$id] : [];
   
}

 $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

function minutosDesde($hora) {
    return (strtotime($hora) - strtotime("6:00 am")) / 60;
}

function convertirHora($hora24) {
    return date("g:i A", strtotime($hora24));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
   <link rel="stylesheet" href="../styles/TEST.css">
</head>
<body>

<?php if ($id !== 999):?>
<div class = "tit"><h1>Opcion de horario numero <?=($id+1)?></h1></div>
<?php endif; ?> 

<div class="cc">
    <?php foreach($dias as $d): ?>
        <span class="nombre-dia"><h3><?= $d; ?></h3></span>
    <?php endforeach; ?>
</div>

<div class="horario">
    <div class="horas">
        <?php for ($h = 6; $h <= 22; $h++): ?>
            <div class="hora"><?= "-     ",date("g:00 a", strtotime("$h:00")); ?></div>
        <?php endfor; ?>
    </div>
    <div class="dias">
        <?php foreach ($dias as $dia): ?>
            <div class="col-dia">
                <div class="contenedor-clases">
                    <?php foreach ($clases as $clase): ?>
                        <?php if ($clase['dia'] !== $dia) continue; ?>
                        <?php
                            $top = minutosDesde($clase['inicio']);
                            $height = minutosDesde($clase['fin']) - minutosDesde($clase['inicio']);
                        ?>
                        <div class="materia <?= $clase['materia']; ?>"
                             style="top: <?= $top; ?>px;
                                    height: <?= $height; ?>px;">
                            <?= $clase['materia']; ?><br>
                            
                            <small><?= convertirHora($clase['inicio']); ?> - <?= convertirHora($clase['fin']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
