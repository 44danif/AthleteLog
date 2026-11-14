<?php

declare(strict_types=1);

// Si una página olvida definir $pageTitle, mostramos un título por defecto
// en lugar de un aviso de PHP.
$pageTitle = $pageTitle ?? 'AthleteLog';

/** @var array $themes Definida en includes/data.php */

// Tema elegido en la cookie. Solo se acepta si es una de las opciones permitidas.
$temaActual = $_COOKIE['theme'] ?? 'dark';
if (!is_string($temaActual) || !array_key_exists($temaActual, $themes)) {
    $temaActual = 'dark';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - AthleteLog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="theme-<?= e($temaActual) ?>">