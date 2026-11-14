<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Página privada: sin sesión, al login.
requireLogin();

$pageTitle = 'Nuevo entrenamiento';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<h1>Nuevo entrenamiento</h1>

<p>Esta página se completará en el paso 6.</p>

<?php require_once __DIR__ . '/includes/footer.php'; ?>