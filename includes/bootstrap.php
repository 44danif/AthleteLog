<?php

declare(strict_types=1);

// La sesión se necesita para guardar el token CSRF de los formularios.
session_start();

// Punto único de carga: cada página solo incluye este fichero
// y así no repetimos la lista de require_once en todas.
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/components.php';
require_once __DIR__ . '/data.php';

// Contador de visitas en la cookie "visits". Va aquí porque setcookie()
// tiene que ejecutarse antes de imprimir HTML, y este fichero se carga el primero.
$visitas = max(0, (int) ($_COOKIE['visits'] ?? 0)) + 1;
guardarCookie('visits', (string) $visitas, time() + COOKIE_DURACION);