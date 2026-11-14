<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/** @var array $sports Definida en includes/data.php */
/** @var array $levels Definida en includes/data.php */
/** @var array $diasSemana Definida en includes/data.php */

$pageTitle = 'Registro';

// Límites de validación en constantes: si cambian, se tocan en un solo sitio
// y el mismo valor sirve para el HTML (maxlength, min, max) y para PHP.
const NOMBRE_MIN   = 3;
const NOMBRE_MAX   = 50;
const PASSWORD_MIN = 8;
const EDAD_MIN     = 16;
const EDAD_MAX     = 60;


// Valores iniciales: el formulario vacío

$nombre   = '';
$usuario  = '';
$email    = '';
$edad     = '';
$deporte  = '';
$nivel    = '';
$dias     = [];
$acepta   = false;
$errores  = [];
$exito    = false;


// LÓGICA: procesar solo si la petición es POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Token CSRF: si no coincide, el envío no viene de nuestro formulario.
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
        http_response_code(403);
        exit('Solicitud no válida');
    }

    // 2. Recoger datos
    $nombre  = campo('nombre');
    $usuario = campo('usuario');
    $email   = campo('email');
    $edad    = campo('edad');
    $deporte = campo('deporte');
    $nivel   = campo('nivel');

    // Las contraseñas NO se recortan con trim(): un espacio puede ser parte de ella.
    $passwordRaw  = $_POST['password'] ?? '';
    $password     = is_string($passwordRaw) ? $passwordRaw : '';
    $password2Raw = $_POST['password2'] ?? '';
    $password2    = is_string($password2Raw) ? $password2Raw : '';

    // Solo nos quedamos con los días que existen en $diasSemana:
    // así se descarta cualquier valor inventado.
    $diasRaw = $_POST['dias'] ?? [];
    $dias = is_array($diasRaw)
        ? array_values(array_intersect(array_keys($diasSemana), array_filter($diasRaw, 'is_string')))
        : [];

    $acepta = isset($_POST['acepto']);

    // 3. Validar
    if ($nombre === '') {
        $errores[] = 'El nombre completo es obligatorio.';
    } elseif (mb_strlen($nombre) < NOMBRE_MIN || mb_strlen($nombre) > NOMBRE_MAX) {
        $errores[] = 'El nombre debe tener entre ' . NOMBRE_MIN . ' y ' . NOMBRE_MAX . ' caracteres.';
    }

    if ($usuario === '') {
        $errores[] = 'El usuario es obligatorio.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{4,15}$/', $usuario)) {
        $errores[] = 'El usuario solo puede tener letras, números y _, entre 4 y 15 caracteres.';
    }

    if ($email === '') {
        $errores[] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email no es válido.';
    }

    if (mb_strlen($password) < PASSWORD_MIN) {
        $errores[] = 'La contraseña debe tener al menos ' . PASSWORD_MIN . ' caracteres.';
    } elseif ($password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    $edadValida = filter_var($edad, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => EDAD_MIN, 'max_range' => EDAD_MAX],
    ]);
    if ($edadValida === false) {
        $errores[] = 'La edad debe ser un número entre ' . EDAD_MIN . ' y ' . EDAD_MAX . '.';
    }

    if (!array_key_exists($deporte, $sports)) {
        $errores[] = 'Selecciona un deporte principal válido.';
    }

    if (!array_key_exists($nivel, $levels)) {
        $errores[] = 'Selecciona un nivel competitivo válido.';
    }

    if ($dias === []) {
        $errores[] = 'Marca al menos un día de entrenamiento.';
    }

    if (!$acepta) {
        $errores[] = 'Debes aceptar el tratamiento de tus datos.';
    }

    // 4. Datos correctos
    if ($errores === []) {
        $exito = true;

        // Nombres legibles de los días para el resumen ("Martes, Jueves").
        $nombresDias = [];
        foreach ($dias as $codigo) {
            $nombresDias[] = $diasSemana[$codigo];
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<?php if ($exito): ?>
    <div class="exito">
        <h1>Registro completado</h1>
        <p>Estos son los datos recibidos:</p>
        <ul>
            <li>Nombre: <strong><?= e($nombre) ?></strong></li>
            <li>Usuario: <strong><?= e($usuario) ?></strong></li>
            <li>Email: <strong><?= e($email) ?></strong></li>
            <li>Edad: <strong><?= e($edad) ?></strong></li>
            <li>Deporte principal: <strong><?= e($sports[$deporte]['name']) ?></strong></li>
            <li>Nivel competitivo: <strong><?= e($levels[$nivel]) ?></strong></li>
            <li>Días de entrenamiento: <strong><?= e(implode(', ', $nombresDias)) ?></strong></li>
        </ul>
    </div>
<?php else: ?>
    <h1>Registro de deportista</h1>

    <?php if ($errores !== []): ?>
        <ul class="errores" role="alert">
            <?php foreach ($errores as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= e($_SERVER['SCRIPT_NAME']) ?>" method="post" class="formulario" novalidate>
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

        <p>
            <label for="nombre">Nombre completo</label>
            <input type="text" name="nombre" id="nombre"
                value="<?= e($nombre) ?>" maxlength="<?= NOMBRE_MAX ?>" required>
        </p>

        <p>
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario"
                value="<?= e($usuario) ?>" maxlength="15" required>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                value="<?= e($email) ?>" required>
        </p>

        <p>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password"
                minlength="<?= PASSWORD_MIN ?>" required>
        </p>

        <p>
            <label for="password2">Repite la contraseña</label>
            <input type="password" name="password2" id="password2"
                minlength="<?= PASSWORD_MIN ?>" required>
        </p>

        <p>
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad"
                value="<?= e($edad) ?>" min="<?= EDAD_MIN ?>" max="<?= EDAD_MAX ?>" required>
        </p>

        <p>
            <label for="deporte">Deporte principal</label>
            <select name="deporte" id="deporte" required>
                <option value="">-- Elige un deporte --</option>
                <?php foreach ($sports as $clave => $datosDeporte): ?>
                    <option value="<?= e($clave) ?>" <?= $clave === $deporte ? 'selected' : '' ?>>
                        <?= e($datosDeporte['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <fieldset>
            <legend>Nivel competitivo</legend>
            <?php foreach ($levels as $clave => $etiqueta): ?>
                <p>
                    <input type="radio" name="nivel" id="nivel_<?= e($clave) ?>"
                        value="<?= e($clave) ?>" <?= $clave === $nivel ? 'checked' : '' ?>>
                    <label for="nivel_<?= e($clave) ?>"><?= e($etiqueta) ?></label>
                </p>
            <?php endforeach; ?>
        </fieldset>

        <fieldset>
            <legend>Días de entrenamiento</legend>
            <?php foreach ($diasSemana as $codigo => $etiqueta): ?>
                <p>
                    <input type="checkbox" name="dias[]" id="dia_<?= e($codigo) ?>"
                        value="<?= e($codigo) ?>" <?= in_array($codigo, $dias, true) ? 'checked' : '' ?>>
                    <label for="dia_<?= e($codigo) ?>"><?= e($etiqueta) ?></label>
                </p>
            <?php endforeach; ?>
        </fieldset>

        <p>
            <input type="checkbox" name="acepto" id="acepto" value="si" <?= $acepta ? 'checked' : '' ?>>
            <label for="acepto">Acepto el tratamiento de mis datos</label>
        </p>

        <button type="submit">Registrarme</button>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>