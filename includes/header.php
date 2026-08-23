<?php

require_once __DIR__ . '/funciones.php';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');

$titulo      = $titulo ?? 'LoginRegister';
$descripcion = $descripcion ?? 'Proyecto personal de login y registro con PHP, MySQL, JavaScript y CSS moderno.';
$base        = $base ?? '';
$pagina      = url_actual();

$enlaces_nav = [
    'index.php'     => 'Inicio',
    'acerca-de.php' => 'Acerca de',
    'faq.php'       => 'FAQ',
    'contacto.php'  => 'Contacto',
];

if (isset($_SESSION['usuario_id'])) {
    $enlaces_nav += [
        'dashboard.php' => 'Mi panel',
        'perfil.php'    => 'Mi perfil',
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($descripcion) ?>">
    <meta name="author" content="Tu Nombre">
    <meta property="og:title" content="<?= e($titulo) ?>">
    <meta property="og:description" content="<?= e($descripcion) ?>">
    <meta property="og:type" content="website">
    <link rel="icon" href="data:,">
    <title><?= e($titulo) ?></title>
    <link rel="stylesheet" href="<?= $base ?>assets/css/styles.css">
    <script>
        (function () {
            var tema = localStorage.getItem('tema');
            if (!tema) {
                tema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oscuro' : 'claro';
            }
            document.documentElement.setAttribute('data-tema', tema);
        })();
    </script>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido principal</a>

    <header class="cabecera">
        <nav aria-label="Navegación principal">
            <a class="logo" href="<?= $base ?>index.php">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                LoginRegister
            </a>

            <button class="nav-alternar" type="button" aria-expanded="false" aria-controls="menu-principal">
                <span class="nav-alternar-caja" aria-hidden="true">
                    <span></span><span></span><span></span>
                </span>
                <span class="visualmente-oculto">Abrir o cerrar el menú</span>
            </button>

            <ul id="menu-principal" class="menu">
                <?php foreach ($enlaces_nav as $archivo => $texto): ?>
                <li>
                    <a href="<?= $base . $archivo ?>" <?= $pagina === $archivo ? 'aria-current="page"' : '' ?>>
                        <?= $texto ?>
                    </a>
                </li>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['usuario_id'])): ?>
                <li><a href="<?= $base ?>logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                <li><a class="boton boton--pequeno" href="<?= $base ?>register.php">Crear cuenta</a></li>
                <?php endif; ?>

                <li>
                    <button class="alternar-tema" type="button"
                            aria-label="Cambiar entre tema claro y oscuro">
                        <svg class="icono-sol" viewBox="0 0 24 24" width="20" height="20" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                        </svg>
                        <svg class="icono-luna" viewBox="0 0 24 24" width="20" height="20" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <?php $avisos_flash = flash_tomar(); ?>
    <?php if ($avisos_flash): ?>
    <div class="avisos" data-avisos>
        <?php foreach ($avisos_flash as $aviso): ?>
        <p class="aviso aviso--<?= e($aviso['tipo']) ?>" role="status"><?= e($aviso['mensaje']) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <main id="contenido">
