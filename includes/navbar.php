<?php

declare(strict_types=1);

// Usuario conectado (o null si no hay sesión)
$usuarioConectado = $_SESSION['user'] ?? null;

// El menú cambia según haya sesión o no. Solo cambia el array:
if ($usuarioConectado === null) {
    $enlacesMenu = [
        'index.php'    => 'Inicio',
        'register.php' => 'Registro',
        'login.php'    => 'Login',
    ];
} else {
    $enlacesMenu = [
        'index.php'       => 'Inicio',
        'training.php'    => 'Nuevo entrenamiento',
        'history.php'     => 'Historial',
        'preferences.php' => 'Preferencias',
        'logout.php'      => 'Cerrar sesión',
    ];
}
?>
<header class="site-header">
    <p class="logo">AthleteLog</p>
    <nav>
        <?php if ($usuarioConectado !== null): ?>
            <span class="saludo">Hola, <?= e($usuarioConectado['name']) ?></span>
        <?php endif; ?>
        <ul>
            <?php foreach ($enlacesMenu as $archivo => $texto): ?>
                <li><a href="<?= e($archivo) ?>"><?= e($texto) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<main>