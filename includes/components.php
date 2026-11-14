<?php

declare(strict_types=1);

// Genera una tabla HTML con una lista de deportes.
// Se usa una función para pintar la tabla de individuales y la de equipo
// sin repetir el mismo HTML dos veces.
function renderSportsTable(array $deportes): string
{
    $html = '<table class="tabla-deportes">'
        . '<thead><tr><th>Deporte</th><th>Unidad de medida</th></tr></thead>'
        . '<tbody>';

    foreach ($deportes as $deporte) {
        $html .= '<tr>'
            . '<td>' . e($deporte['name']) . '</td>'
            . '<td>' . e($deporte['unit']) . '</td>'
            . '</tr>';
    }

    return $html . '</tbody></table>';
}

//Devuelve en HTML el mensaje de bienvenida

function renderMnsBienv():string{
    $nombreApp = e(APP_NAME);

    return <<<HTML
        <h1>Bienvenido/a a {$nombreApp}</h1>

        <p>
            AthleteLog es tu diario de entrenamiento para deportistas de competición.
            Regístrate, anota cada sesión y consulta tu historial para entrenar con método.
        </p>
    HTML;
}

// Tabla con los entrenamientos guardados en la sesión.
function renderTrainingTable(array $trainings): string
{
    $html = '<table class="tabla-entrenamientos">'
        . '<thead><tr>'
        . '<th>#</th><th>Fecha</th><th>Tipo</th><th>Minutos</th><th>RPE</th>'
        . '<th>Carga</th><th>Sensaciones</th><th>Lesión / molestia</th>'
        . '</tr></thead><tbody>';

    foreach ($trainings as $numero => $entrenamiento) {
        // La fecha se guarda como AAAA-MM-DD y se muestra como DD/MM/AAAA.
        $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $entrenamiento['date']);

        $html .= '<tr>'
            . '<td>' . e($numero + 1) . '</td>'
            . '<td>' . e($fecha ? $fecha->format('d/m/Y') : $entrenamiento['date']) . '</td>'
            . '<td>' . e(TRAINING_TYPES[$entrenamiento['type']]) . '</td>'
            . '<td>' . e($entrenamiento['duration']) . '</td>'
            . '<td>' . e($entrenamiento['rpe']) . '</td>'
            . '<td>' . e(calculateLoad($entrenamiento['duration'], $entrenamiento['rpe'])) . '</td>'
            . '<td>' . e($entrenamiento['notes'] !== '' ? $entrenamiento['notes'] : '—') . '</td>'
            . '<td>' . e($entrenamiento['injury'] !== '' ? $entrenamiento['injury'] : 'No') . '</td>'
            . '</tr>';
    }

    return $html . '</tbody></table>';
}