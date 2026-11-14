<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Página privada: sin sesión, al login.
requireLogin();

$pageTitle = 'Nuevo entrenamiento';


// Valores iniciales: el formulario vacío (la fecha propuesta es hoy)

$input = [
    'date'       => date('Y-m-d'),
    'type'       => '',
    'duration'   => '',
    'rpe'        => '',
    'notes'      => '',
    'has_injury' => false,
    'injury'     => '',
];
$errores = [];

// LÓGICA: procesar solo si la petición es POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recoger los datos en un array asociativo
    $input = [
        'date'       => campo('fecha'),
        'type'       => campo('tipo'),
        'duration'   => campo('duracion'),
        'rpe'        => campo('rpe'),
        'notes'      => campo('sensaciones'),
        'has_injury' => isset($_POST['lesion']),
        'injury'     => campo('lesion_detalle'),
    ];

    // 2. Validar
    $errores = validateTraining($input);

    // 3. Si todo está bien, construir el entrenamiento y guardarlo en la sesión
    if ($errores === []) {
        $entrenamiento = [
            'date'     => $input['date'],
            'type'     => $input['type'],
            'duration' => (int) $input['duration'],
            'rpe'      => (int) $input['rpe'],
            'notes'    => $input['notes'],
            // Si no marcó la casilla, se guarda vacío aunque haya escrito algo.
            'injury'   => $input['has_injury'] ? $input['injury'] : '',
        ];

        // [] añade el entrenamiento al final: la sesión guarda un array de arrays.
        $_SESSION['trainings'][] = $entrenamiento;

        // Se recarga la página: así F5 no vuelve a guardar el mismo entrenamiento.
        header('Location: training.php?guardado=1');
        exit;
    }
}

// Entrenamientos de la sesión y sus estadísticas
$trainings = $_SESSION['trainings'] ?? [];
$stats     = getTrainingStats($trainings);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<h1>Nuevo entrenamiento</h1>

<?php if (isset($_GET['guardado'])): ?>
    <p class="aviso">Entrenamiento guardado.</p>
<?php endif; ?>

<?php if ($errores !== []): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= e($_SERVER['SCRIPT_NAME']) ?>" method="post" class="formulario" novalidate>

    <p>
        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha"
            value="<?= e($input['date']) ?>" max="<?= e(date('Y-m-d')) ?>" required>
    </p>

    <fieldset>
        <legend>Tipo de sesión</legend>
        <?php foreach (TRAINING_TYPES as $clave => $etiqueta): ?>
            <p>
                <input type="radio" name="tipo" id="tipo_<?= e($clave) ?>"
                    value="<?= e($clave) ?>" <?= $clave === $input['type'] ? 'checked' : '' ?>>
                <label for="tipo_<?= e($clave) ?>"><?= e($etiqueta) ?></label>
            </p>
        <?php endforeach; ?>
    </fieldset>

    <p>
        <label for="duracion">Duración (minutos)</label>
        <input type="number" name="duracion" id="duracion" value="<?= e($input['duration']) ?>"
            min="<?= DURACION_MIN ?>" max="<?= DURACION_MAX ?>" required>
    </p>

    <fieldset>
        <legend>Esfuerzo percibido (RPE)</legend>
        <?php for ($valor = RPE_MIN; $valor <= RPE_MAX; $valor++): ?>
            <p>
                <input type="radio" name="rpe" id="rpe_<?= $valor ?>"
                    value="<?= $valor ?>" <?= (string) $valor === $input['rpe'] ? 'checked' : '' ?>>
                <label for="rpe_<?= $valor ?>"><?= $valor ?></label>
            </p>
        <?php endfor; ?>
    </fieldset>

    <p>
        <label for="sensaciones">Sensaciones</label>
        <textarea name="sensaciones" id="sensaciones"
            maxlength="<?= SENSACIONES_MAX ?>"><?= e($input['notes']) ?></textarea>
    </p>

    <div class="campo-lesion">
        <input type="checkbox" name="lesion" id="lesion" value="1" <?= $input['has_injury'] ? 'checked' : '' ?>>
        <label for="lesion">He tenido alguna lesión o molestia</label>

        <p class="lesion-detalle">
            <label for="lesion_detalle">Describe la lesión o molestia</label>
            <input type="text" name="lesion_detalle" id="lesion_detalle" value="<?= e($input['injury']) ?>">
        </p>
    </div>

    <button type="submit">Guardar entrenamiento</button>
</form>

<h2>Entrenamientos de esta sesión</h2>

<?php if ($trainings === []): ?>
    <p>Todavía no has registrado ningún entrenamiento.</p>
<?php else: ?>
    <?= renderTrainingTable($trainings) ?>

    <h2>Estadísticas</h2>
    <div class="estadisticas">
        <div class="estadistica">
            <span class="estadistica-valor"><?= e($stats['sessions']) ?></span>
            <span class="estadistica-nombre">Sesiones</span>
        </div>
        <div class="estadistica">
            <span class="estadistica-valor"><?= e($stats['minutes']) ?></span>
            <span class="estadistica-nombre">Minutos totales</span>
        </div>
        <div class="estadistica">
            <span class="estadistica-valor"><?= e(number_format($stats['rpe_avg'], 1, ',', '.')) ?></span>
            <span class="estadistica-nombre">RPE medio</span>
        </div>
        <div class="estadistica">
            <span class="estadistica-valor"><?= e($stats['load']) ?></span>
            <span class="estadistica-nombre">Carga total</span>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>