<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/** @var array $sports Definida en includes/data.php */

$pageTitle = 'Inicio';

// Separamos los deportes antes del HTML para que la plantilla solo pinte.
$deportesIndividuales = getSportsByCategory($sports, 'individual');
$deportesEquipo       = getSportsByCategory($sports, 'equipo');

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<?= renderMnsBienv() ?>

<h2>Deportes individuales</h2>
<?= renderSportsTable($deportesIndividuales) ?>

<h2>Deportes de equipo</h2>
<?= renderSportsTable($deportesEquipo) ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>