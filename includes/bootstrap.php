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