<?php

declare(strict_types=1);

// Datos maestros de la aplicación. La clave de cada array es el identificador
// técnico (lo que se guarda y se envía en formularios) y el valor, lo que se muestra.

$sports = [
    'running' => [
        'name'     => 'Running',
        'category' => 'individual',
        'unit'     => 'km',
    ],
    'natacion' => [
        'name'     => 'Natación',
        'category' => 'individual',
        'unit'     => 'metros',
    ],
    'ciclismo' => [
        'name'     => 'Ciclismo',
        'category' => 'individual',
        'unit'     => 'km',
    ],
    'halterofilia' => [
        'name'     => 'Halterofilia',
        'category' => 'individual',
        'unit'     => 'kg',
    ],
    'futbol' => [
        'name'     => 'Fútbol',
        'category' => 'equipo',
        'unit'     => 'minutos',
    ],
    'baloncesto' => [
        'name'     => 'Baloncesto',
        'category' => 'equipo',
        'unit'     => 'minutos',
    ],
    'balonmano' => [
        'name'     => 'Balonmano',
        'category' => 'equipo',
        'unit'     => 'minutos',
    ],
];

$levels = [
    'beginner'      => 'Iniciación',
    'regional'      => 'Regional',
    'national'      => 'Nacional',
    'international' => 'Internacional',
];

// Las contraseñas se guardan solo como hash generado con password_hash():
// aunque alguien lea este fichero, no puede saber la contraseña original.
$users = [
    'laura_runner' => [
        'name'          => 'Laura Gómez',
        'password_hash' => '$2y$10$LpdPDDWz5.8PfUFJ4cnieuqF6vozKhOVbpZufXktbVP0gZNABuwSa',
        'sport'         => 'running',
    ],
    'marcos_bk' => [
        'name'          => 'Marcos Ruiz',
        'password_hash' => '$2y$10$YDygD/UF4EEl49DmZ4b2POyjBUGMz7pmzwC8YQ57x2LAELonFz9Zy',
        'sport'         => 'baloncesto',
    ],
    'sara_swim' => [
        'name'          => 'Sara López',
        'password_hash' => '$2y$10$IyauYtH8KmthjFdCpA5L/.ovIK0PzZembSKwAEEu954ePIS7fhFl.',
        'sport'         => 'natacion',
    ],
];

// Días posibles para los checkboxes de registro.
$diasSemana = [
    'lunes'     => 'Lunes',
    'martes'    => 'Martes',
    'miercoles' => 'Miércoles',
    'jueves'    => 'Jueves',
    'viernes'   => 'Viernes',
    'sabado'    => 'Sábado',
    'domingo'   => 'Domingo',
];
$themes = [
    'light' => 'Claro',
    'dark'  => 'Oscuro',
];

$weightUnits = [
    'kg' => 'Kilogramos (kg)',
    'lb' => 'Libras (lb)',
];