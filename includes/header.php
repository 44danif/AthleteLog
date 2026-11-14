<?php

declare(strict_types=1);

// Si una página olvida definir $pageTitle, mostramos un título por defecto
// en lugar de un aviso de PHP.
$pageTitle = $pageTitle ?? 'AthleteLog';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - AthleteLog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>