<?php

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_validar(?string $token): bool
{
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function flash_tomar(): array
{
    $avisos = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $avisos;
}

function url_actual(): string
{
    return basename($_SERVER['SCRIPT_NAME']);
}

function fecha_legible(?string $fecha): string
{
    if (!$fecha) {
        return 'Primera visita';
    }

    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
    ];

    $dt = new DateTimeImmutable($fecha);

    return $dt->format('j') . ' de ' . $meses[(int) $dt->format('n')] . ' de ' . $dt->format('Y')
        . ' a las ' . $dt->format('H:i');
}
