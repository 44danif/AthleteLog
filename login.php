<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/** @var array $users Definida en includes/data.php */

$pageTitle = 'Login';

$usuario = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario     = campo('usuario');
    $passwordRaw = $_POST['password'] ?? '';
    $password    = is_string($passwordRaw) ? $passwordRaw : '';

    $datosUsuario = $users[$usuario] ?? null;

    if ($datosUsuario !== null && password_verify($password, $datosUsuario['password_hash'])) {
        // Nuevo identificador de sesión al entrar (evita la fijación de sesión)
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'username' => $usuario,
            'name'     => $datosUsuario['name'],
            'sport'    => $datosUsuario['sport'],
        ];

        header('Location: index.php');
        exit;
    }

    // Mensaje genérico: no dice si falló el usuario o la contraseña
    $error = 'Usuario o contraseña incorrectos.';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<h1>Iniciar sesión</h1>

<?php if ($error !== ''): ?>
    <ul class="errores" role="alert">
        <li><?= e($error) ?></li>
    </ul>
<?php endif; ?>

<form action="<?= e($_SERVER['SCRIPT_NAME']) ?>" method="post" class="formulario" novalidate>
    <p>
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" value="<?= e($usuario) ?>" required>
    </p>

    <p>
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>
    </p>

    <button type="submit">Entrar</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>