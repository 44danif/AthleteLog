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