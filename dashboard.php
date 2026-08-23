<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$titulo = 'Mi panel | LoginRegister';
$descripcion = 'Panel privado del usuario en LoginRegister.';

require_once __DIR__ . '/includes/header.php';
?>

<article>
    <h1>Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h1>
    <p>Has iniciado sesión correctamente. Esta página solo es visible para usuarios autenticados.</p>
</article>

<section>
    <h2>Datos de la sesión</h2>
    <dl>
        <dt>ID de usuario</dt>
        <dd><?= (int) $_SESSION['usuario_id'] ?></dd>

        <dt>Nombre</dt>
        <dd><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></dd>
    </dl>

    <p><a class="boton boton-secundario" href="logout.php">Cerrar sesión</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
