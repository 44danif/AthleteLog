<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/** @var array $themes Definida en includes/data.php */
/** @var array $weightUnits Definida en includes/data.php */

// Página privada: sin sesión, al login.
requireLogin();

$pageTitle = 'Preferencias';


// Valores actuales: los guardados en las cookies (o los de por defecto)

$tema = $_COOKIE['theme'] ?? 'dark';
if (!is_string($tema) || !array_key_exists($tema, $themes)) {
    $tema = 'dark';
}

$unidad = $_COOKIE['weight_unit'] ?? 'kg';
if (!is_string($unidad) || !array_key_exists($unidad, $weightUnits)) {
    $unidad = 'kg';
}

$consejos = ($_COOKIE['show_tips'] ?? '') === '1';
$errores  = [];


// LÓGICA: procesar solo si la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Botón "Restablecer": se borran las cookies poniéndoles una caducidad en el pasado.
    if (isset($_POST['restablecer'])) {
        guardarCookie('theme', '', time() - 3600);
        guardarCookie('weight_unit', '', time() - 3600);
        guardarCookie('show_tips', '', time() - 3600);

        header('Location: preferences.php?restablecido=1');
        exit;
    }

    // Botón "Guardar"
    $tema     = campo('tema');
    $unidad   = campo('unidad');
    $consejos = isset($_POST['consejos']);

    // Solo se aceptan las opciones que ofrecemos, aunque el cliente envíe otra cosa.
    if (!array_key_exists($tema, $themes)) {
        $errores[] = 'Elige un tema válido.';
    }
    if (!array_key_exists($unidad, $weightUnits)) {
        $errores[] = 'Elige una unidad de peso válida.';
    }

    if ($errores === []) {
        $caducidad = time() + COOKIE_DURACION;

        guardarCookie('theme', $tema, $caducidad);
        guardarCookie('weight_unit', $unidad, $caducidad);
        guardarCookie('show_tips', $consejos ? '1' : '0', $caducidad);

        // Se recarga la página: las cookies nuevas solo llegan en la siguiente petición.
        header('Location: preferences.php?guardado=1');
        exit;
    }
}

// Mensaje tras guardar o restablecer
$mensaje = '';
if (isset($_GET['guardado'])) {
    $mensaje = 'Preferencias guardadas durante 30 días.';
} elseif (isset($_GET['restablecido'])) {
    $mensaje = 'Preferencias restablecidas.';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<h1>Preferencias</h1>

<?php if ($mensaje !== ''): ?>
    <p class="aviso"><?= e($mensaje) ?></p>
<?php endif; ?>

<?php if ($errores !== []): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= e($_SERVER['SCRIPT_NAME']) ?>" method="post" class="formulario" novalidate>

    <fieldset>
        <legend>Tema</legend>
        <?php foreach ($themes as $clave => $etiqueta): ?>
            <p>
                <input type="radio" name="tema" id="tema_<?= e($clave) ?>"
                    value="<?= e($clave) ?>" <?= $clave === $tema ? 'checked' : '' ?>>
                <label for="tema_<?= e($clave) ?>"><?= e($etiqueta) ?></label>
            </p>
        <?php endforeach; ?>
    </fieldset>

    <p>
        <label for="unidad">Unidad de peso</label>
        <select name="unidad" id="unidad">
            <?php foreach ($weightUnits as $clave => $etiqueta): ?>
                <option value="<?= e($clave) ?>" <?= $clave === $unidad ? 'selected' : '' ?>>
                    <?= e($etiqueta) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <input type="checkbox" name="consejos" id="consejos" value="1" <?= $consejos ? 'checked' : '' ?>>
        <label for="consejos">Mostrar consejos</label>
    </p>

    <div class="botones">
        <button type="submit" name="guardar">Guardar preferencias</button>
        <button type="submit" name="restablecer" class="boton-secundario">Restablecer preferencias</button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>