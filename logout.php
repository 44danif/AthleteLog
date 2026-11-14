<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Vaciar los datos de la sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: login.php');
exit;