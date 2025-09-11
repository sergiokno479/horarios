<?php

session_start();

$matriz = $_SESSION['horario'];

$horario = ["lunes" => [],"martes" => [],"miercoles" => [],"jueves" => [],"viernes" => [],"sabado" => [],"domingo" => []];
$materias = [];
$formal = [];
$decision =  2;
backtrack(0, $matriz, $horario, $materias, $formal, $todasLasCombinaciones);

//$todasLasCombinaciones['clases], $todasLasCombinaciones['resumen'], $todasLasCombinaciones['materias]

$PPP = procesarCombinaciones($todasLasCombinaciones, $decision);

$_SESSION['resfinal'] = $PPP;

//print_r($_SESSION['resfinal']);

function procesarCombinaciones(array $todasLasCombinaciones, int $decision): array {
    $adaptado = [];

    foreach ($todasLasCombinaciones as $combo) {
        $resumen = $combo['resumen'];
        $materias = $combo['materias'];

        $adaptado[] = [
            'materias' => $materias,
            'resumen' => $resumen,
            'clases' => $combo['clases']
        ];
    }

    $ordenado = sortear($adaptado, $decision);
    return array_slice($ordenado, 0, 15);
}

function sortear(array $horarios, int $preferencia): array {
    // numero de clases en la semana, horas de estudio, numero de huecos,tamaño de huecos, dias libres, dias ocupados, numero de materias
    // print_r($horarios[0]['resumen']);
    usort($horarios, function ($a, $b) use ($preferencia) {
        $a = $a['resumen'];
        $b = $b['resumen'];
        if ($a['clasestot'] !== $b['clasestot']) {
            return $b['clasestot'] - $a['clasestot'];
        }
        
        switch ($preferencia) {
            case 1:return $a['tenlau'] <=> $b['tenlau'];
            case 2:return $b['tenlau'] <=> $a['tenlau'];
            case 3:return $a['docupados'] <=> $b['docupados'];
            case 4:return $b['docupados'] <=> $a['docupados'];
            case 5:  return $a['nhuecos'] <=> $b['nhuecos'];
            case 6: 
                if($a['nhuecos'] === $b['nhuecos']){
                    return $b['tamhuecos'] <=> $a['tamhuecos'];
                }return $a['nhuecos'] <=> $b['nhuecos'];
            case 7:return $b['nhuecos'] <=> $a['nhuecos'];
            case 8:
                if ($a['docupados'] === $b['docupados']){
                    return $b['tenlau'] <=> $a['tenlau'];
                }return $a['docupados'] <=> $b['docupados'];
            case 9:
                $a_score = $a['nhuecos'] + $a['docupados'] + $a['tenlau'];
                $b_score = $b['nhuecos'] + $b['docupados'] + $b['tenlau'];
                return $a_score <=> $b_score;
            case 10:
                if ($a['tamhuecos'] === $b['tamhuecos']) {
                    return $a['docupados'] <=> $b['docupados'];
                }return $b['tamhuecos'] <=> $a['tamhuecos'];
            default:
                return 0;
        }
    });

    return $horarios;
}


function backtrack($idx, $matriz, &$horario, &$materias, &$formal, &$todasLasCombinaciones) {
    if ($idx >= count($matriz)) {
        $tempHorario = ["lunes" => [], "martes" => [], "miercoles" => [], "jueves" => [], "viernes" => [], "sabado" => [], "domingo" => []];
        foreach ($formal as $item) {
            $inicio = nbr($item['inicio']);
            $fin = nbr($item['fin']);
            $tempHorario[$item['dia']][] = $inicio;
            $tempHorario[$item['dia']][] = $fin;
        }
        srt($tempHorario);
        $resumen = study($tempHorario,$materias);
        
        $todasLasCombinaciones[] = [
            "clases" => $formal,
            "resumen" => $resumen,
            "materias" => $materias
        ];
        return;
    }

    for ($i = $idx; $i < count($matriz); $i++) {
        if (canit($horario, $matriz[$i], $materias)) {
            $copiah = $horario;
            $copiam = $materias;
            $copiarf = $formal;

            add($horario, $materias, $matriz[$i], $formal);
            backtrack($i + 1, $matriz, $horario, $materias, $formal, $todasLasCombinaciones);

            $horario = $copiah;
            $materias = $copiam;
            $formal = $copiarf;
        }
    }
}

function add(&$horario, &$materias, $dia, &$formal) {
    foreach ($dia['info'] as $key) {
        $d1 = nbr($key[1]);
        $d2 = nbr($key[2]);
        $horario[$key[0]][] = $d1;
        $horario[$key[0]][] = $d2;
        $formal[] = ['materia' => $dia['asignatura'], 'dia' => $key[0], 'inicio' => $key[1], 'fin' => $key[2]];
    }
    $materias[$dia['asignatura']] = $dia['crn'];
}

function canit($horario, $dia, $materias) {
    if (array_key_exists($dia['asignatura'], $materias)) {
        return false;
    }

    foreach ($dia['info'] as $key) {
        for ($i = 0; $i < sizeof($horario[$key[0]]); $i += 2) {
            $a = $horario[$key[0]][$i];
            $b = $horario[$key[0]][$i + 1];
            $x = nbr($key[1]);
            $y = nbr($key[2]);

            if (($x == $a && $y == $b) || ($x > $a && $x < $b) || ($y > $a && $y < $b)) {
                return false;
            }
        }
    }

    return true;
}

function study($semana,$materias) {
    srt($semana);
    $nclases = 0; $hdeestudio = 0; $nhuecos = 0; $tenlau = 0;
    $tamhuecos = 0; $dlibres = 0; $docupados = 0;

    foreach ($semana as $key) {
        $n = sizeof($key);
        $nclases += ($n / 2);
        if ($n != 0) {
            $docupados++;
            $tenlau += ($key[$n - 1] - $key[0]);
            for ($i = 1; $i < $n; $i += 2) {
                $ini = $key[$i - 1];
                $fin = $key[$i];
                $hdeestudio += $fin - $ini;
                if ($i != ($n - 1)) {
                    if ($key[$i + 1] - $key[$i] > 20) {
                        $nhuecos++;
                        $tamhuecos += ($key[$i + 1] - $key[$i]);
                    }
                }
            }
        } else {
            $dlibres++;
        }
    }

    return ['nclases' => $nclases,'hdeestudio' => $hdeestudio,'nhuecos' => $nhuecos,'tamhuecos' => $tamhuecos,
    'dlibres' => $dlibres,'docupados' => $docupados,'tenlau' => $tenlau,'clasestot' => sizeof($materias)];

}

function nbr($numero) {
    $p = explode(":", date("H:i", strtotime($numero)));
    return ($p[0] * 60) + $p[1];
}

function srt(&$semana) {
    foreach ($semana as &$key) {
        sort($key);
    }
    unset($key);
}
?>
