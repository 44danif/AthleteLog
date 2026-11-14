<?php

declare(strict_types=1);

// Escapa cualquier dato antes de imprimirlo en HTML para evitar XSS.
// ENT_QUOTES también escapa comillas simples, necesario dentro de atributos.
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Devuelve solo los deportes de una categoría ('individual' o 'equipo').
// Se conservan las claves para poder seguir identificando cada deporte.
function getSportsByCategory(array $sports, string $category): array
{
    $resultado = [];

    foreach ($sports as $clave => $deporte) {
        if ($deporte['category'] === $category) {
            $resultado[$clave] = $deporte;
        }
    }

    return $resultado;
}

// Lee un campo de texto de $_POST. Si no llega o llega como array
// (alguien manipulando el formulario), devuelve cadena vacía.
function campo(string $nombre): string
{
    $valor = $_POST[$nombre] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

// Devuelve el token CSRF de la sesión, creándolo la primera vez.
function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function requireLogin(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

// Guarda una cookie con las opciones que pide el enunciado.
// Para borrarla se llama con una caducidad en el pasado.
function guardarCookie(string $nombre, string $valor, int $caducidad): void
{
    setcookie($nombre, $valor, [
        'expires'  => $caducidad,  // momento en que caduca (timestamp)
        'path'     => '/',         // vale para toda la web
        'httponly' => true,        // JavaScript no puede leerla
        'samesite' => 'Lax',       // no se envía en POST que vienen de otras webs
    ]);
}


// Entrenamientos (Paso 6)


// Valida los datos de un entrenamiento y devuelve la lista de errores
// (vacía si todo está bien).
function validateTraining(array $input): array
{
    $errores = [];

    // Fecha: tiene que ser una fecha real (AAAA-MM-DD)
    $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $input['date']);
    if ($input['date'] === '') {
        $errores[] = 'La fecha es obligatoria.';
    } elseif ($fecha === false || $fecha->format('Y-m-d') !== $input['date']) {
        $errores[] = 'La fecha no es válida.';
    } elseif ($input['date'] > date('Y-m-d')) {
        $errores[] = 'La fecha no puede ser futura.';
    }

    if (!array_key_exists($input['type'], TRAINING_TYPES)) {
        $errores[] = 'Elige un tipo de sesión válido.';
    }

    $duracion = filter_var($input['duration'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => DURACION_MIN, 'max_range' => DURACION_MAX],
    ]);
    if ($duracion === false) {
        $errores[] = 'La duración debe ser un número entero entre ' . DURACION_MIN . ' y ' . DURACION_MAX . ' minutos.';
    }

    $rpe = filter_var($input['rpe'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => RPE_MIN, 'max_range' => RPE_MAX],
    ]);
    if ($rpe === false) {
        $errores[] = 'El esfuerzo percibido (RPE) debe ser un entero entre ' . RPE_MIN . ' y ' . RPE_MAX . '.';
    }

    if (mb_strlen($input['notes']) > SENSACIONES_MAX) {
        $errores[] = 'Las sensaciones no pueden superar los ' . SENSACIONES_MAX . ' caracteres.';
    }

    // Campo condicional: la descripción solo es obligatoria si se marca la casilla.
    if ($input['has_injury'] && $input['injury'] === '') {
        $errores[] = 'Has marcado lesión o molestia: describe qué te pasa.';
    }

    return $errores;
}

// Carga del entrenamiento: minutos × esfuerzo percibido.
function calculateLoad(int $duration, int $rpe): int
{
    return $duration * $rpe;
}

// Estadísticas de una lista de entrenamientos.
function getTrainingStats(array $trainings): array
{
    $totalSesiones  = count($trainings);
    $minutosTotales = array_sum(array_column($trainings, 'duration'));

    $cargaTotal = 0;
    foreach ($trainings as $entrenamiento) {
        $cargaTotal += calculateLoad($entrenamiento['duration'], $entrenamiento['rpe']);
    }

    // Sin sesiones no se puede dividir: la media es 0.
    $rpeMedio = $totalSesiones > 0
        ? array_sum(array_column($trainings, 'rpe')) / $totalSesiones
        : 0;

    return [
        'sessions' => $totalSesiones,
        'minutes'  => $minutosTotales,
        'rpe_avg'  => round($rpeMedio, 1),
        'load'     => $cargaTotal,
    ];
}