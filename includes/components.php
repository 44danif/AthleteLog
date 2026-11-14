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