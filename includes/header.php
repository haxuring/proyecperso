<?php

$titulo = $titulo ?? 'LoginRegister';
$descripcion = $descripcion ?? 'Proyecto personal de login y registro desarrollado con PHP y MySQL.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($descripcion) ?>">
    <meta name="author" content="Tu Nombre">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav aria-label="Navegación principal">
            <a class="logo" href="index.php">LoginRegister</a>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                <li><a href="dashboard.php">Mi panel</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                <li><a href="register.php">Registro</a></li>
                <li><a href="login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
