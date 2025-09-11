<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode($_POST['datos_materias'], true);
    $_SESSION['horario'] = $datos;
    header("Location: pregunta.php");    
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Horario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="../scripts/agghor.js"></script>
  <link rel="stylesheet" href="../styles/agghor.css">

</head>
<body class="bg-light">

<div class="header">
     <a href = "main.php"><div style="font-size: 1.5em; font-weight: bold;">UniTime</div></a>
     <a href = "../authen/perfil.php"><div style="font-size: 1.1em; font-weight: bold;"><?="Bienvenido ".$_SESSION['nombre']?></div></a>
</div>


<div class="container py-5">
  <h1 class="text-center mb-4">Agregar Clases</h1>
  <form id="clasesForm" method="POST">
    <div id="clasesContainer"></div>  
    <div class="text-center mt-4">
      <button type="button" class="btn btn-primary me-2" onclick="agregarClase()">Agregar Clase</button>
      <button type="submit" class="btn btn-success me-2">Guardar Horario</button>
      <button type="button" class="btn btn-warning" onclick="simularDatos()">Simular</button>

    </div>
  </form>
</div>

<script>
  
let contadorClases = 0;
const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

function agregarClase() {
  contadorClases++;
    
  const claseHTML = `
    <div class="position-relative border p-3 mb-4 rounded bg-light" id="clase-${contadorClases}">
      <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="eliminarClase(${contadorClases})">
        Eliminar clase
      </button>

      <div class="row">
        <div class="col-md-6 mb-2">
          <label>Nombre de la Materia:</label>
          <input type="text" name="materia-${contadorClases}" class="form-control" required>
        </div>
        <div class="col-md-3 mb-2">
          <label>NRC:</label>
          <input type="text" name="nrc-${contadorClases}" class="form-control" required>
        </div>
      </div>

      <div class="mb-2">
        <label>Días de Clase:</label>
        <div id="dias-clase-${contadorClases}" class="form-check">
          ${diasSemana.map(dia => `
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="dia-${dia}-${contadorClases}" value="${dia}" onchange="agregarHorarioPorDia(${contadorClases}, '${dia}')">
              <label class="form-check-label" for="dia-${dia}-${contadorClases}">${dia}</label>
            </div>
          `).join('')}
        </div>
      </div>

      <div id="horarios-${contadorClases}"></div>
    </div>`;

  const container = document.getElementById('clasesContainer');
  container.insertAdjacentHTML('beforeend', claseHTML);
}

function agregarHorarioPorDia(idClase, dia) {
  const checkbox = document.getElementById(`dia-${dia}-${idClase}`);
  const horariosContainer = document.getElementById(`horarios-${idClase}`);

  if (checkbox.checked) {
    const horarioHTML = `
      <div class="mb-2" id="horario-${dia}-${idClase}">
        <label> Horario del día ${dia}</label>
        <div class="row">
          <div class="col-md-5">
            <input type="time" name="entrada-${dia}-${idClase}" class="form-control" required>
          </div>
          <div class="col-md-1 text-center">hasta</div>
          <div class="col-md-5">
            <input type="time" name="salida-${dia}-${idClase}" class="form-control" required>
          </div>
        </div>
      </div>
    `;
    horariosContainer.insertAdjacentHTML('beforeend', horarioHTML);
  } else {
    const horarioInput = document.getElementById(`horario-${dia}-${idClase}`);
    if (horarioInput) {
      horarioInput.remove();
    }
  }
}

function eliminarClase(id) {
  const clase = document.getElementById(`clase-${id}`);
  if (clase) {
    clase.remove();
  }
}

document.getElementById("clasesForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const clases = [];

  for (let i = 1; i <= contadorClases; i++) {
    const claseDiv = document.getElementById(`clase-${i}`);
    if (!claseDiv) continue;

    const materia = document.querySelector(`[name="materia-${i}"]`).value;
    const nrc = document.querySelector(`[name="nrc-${i}"]`).value;

    const horarios = [];

    diasSemana.forEach(dia => {
      const checkbox = document.getElementById(`dia-${dia}-${i}`);
      if (checkbox && checkbox.checked) {
        const entrada = document.querySelector(`[name="entrada-${dia}-${i}"]`)?.value;
        const salida = document.querySelector(`[name="salida-${dia}-${i}"]`)?.value;

        horarios.push([dia.toLowerCase(), entrada, salida]);
      }
    });

    clases.push({
      asignatura: materia,
      crn: nrc,
      info: horarios
    });
  }

  const inputHidden = document.createElement("input");
  inputHidden.type = "hidden";
  inputHidden.name = "datos_materias";
  inputHidden.value = JSON.stringify(clases);

  this.appendChild(inputHidden);
  this.submit();
});

document.addEventListener('DOMContentLoaded', () => {
  agregarClase();
});

const datosSimulados = [
  {
    asignatura: "algebra lineal",
    crn: "7894",
    info: [["lunes", "08:00", "10:00"], ["jueves", "14:00", "16:00"]]
  },
  {
    asignatura: "fisica 1",
    crn: "5231",
    info: [["martes", "09:00", "11:00"], ["jueves", "14:00", "16:00"]]
  },
  {
    asignatura: "programacion en Java",
    crn: "6342",
    info: [["lunes", "10:00", "12:00"], ["miercoles", "08:00", "10:00"], ["viernes", "13:30", "15:30"]]
  },
  {
    asignatura: "estructura de datos",
    crn: "8472",
    info: [["martes", "07:30", "09:30"], ["jueves", "10:00", "12:00"], ["sabado", "08:00", "10:00"]]
  },
  {
    asignatura: "quimica general",
    crn: "9123",
    info: [["lunes", "13:00", "15:00"]]
  },
  {
    asignatura: "calculo vectorial",
    crn: "7564",
    info: [["martes", "11:00", "13:00"]]
  }
];

function simularDatos() {
  // Limpiar clases anteriores
  document.getElementById('clasesContainer').innerHTML = '';
  contadorClases = 0;

  datosSimulados.forEach(clase => {
    agregarClase();
    const index = contadorClases;

    document.querySelector(`[name="materia-${index}"]`).value = clase.asignatura;
    document.querySelector(`[name="nrc-${index}"]`).value = clase.crn;

    clase.info.forEach(([dia, entrada, salida]) => {
      dia = dia.charAt(0).toUpperCase() + dia.slice(1); // Capitalizar para coincidir con ID
      const checkbox = document.getElementById(`dia-${dia}-${index}`);
      if (checkbox) {
        checkbox.checked = true;
        agregarHorarioPorDia(index, dia);

        const entradaInput = document.querySelector(`[name="entrada-${dia}-${index}"]`);
        const salidaInput = document.querySelector(`[name="salida-${dia}-${index}"]`);
        if (entradaInput && salidaInput) {
          entradaInput.value = entrada;
          salidaInput.value = salida;
        }
      }
    });
  });

  // Crear un input oculto con los datos simulados y enviarlos
  const clases = [];
  for (let i = 1; i <= contadorClases; i++) {
    const claseDiv = document.getElementById(`clase-${i}`);
    if (!claseDiv) continue;

    const materia = document.querySelector(`[name="materia-${i}"]`).value;
    const nrc = document.querySelector(`[name="nrc-${i}"]`).value;

    const horarios = [];
    diasSemana.forEach(dia => {
      const checkbox = document.getElementById(`dia-${dia}-${i}`);
      if (checkbox && checkbox.checked) {
        const entrada = document.querySelector(`[name="entrada-${dia}-${i}"]`)?.value;
        const salida = document.querySelector(`[name="salida-${dia}-${i}"]`)?.value;

        horarios.push([dia.toLowerCase(), entrada, salida]);
      }
    });

    clases.push({
      asignatura: materia,
      crn: nrc,
      info: horarios
    });
  }

  // Crear input hidden con los datos de las materias
  const inputHidden = document.createElement("input");
  inputHidden.type = "hidden";
  inputHidden.name = "datos_materias";
  inputHidden.value = JSON.stringify(clases);

  // Enviar el formulario con los datos
  const form = document.getElementById("clasesForm");
  form.appendChild(inputHidden);
  form.submit();
}

</script>

</body>

</html>
