<?php

session_start();
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/config/database.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validar($_POST['csrf_token'] ?? null)) {
        $errores[] = 'Sesión de seguridad caducada. Vuelve a enviar el formulario.';
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asunto = $_POST['asunto'] ?? '';
    $mensaje = trim($_POST['mensaje'] ?? '');
    $asuntos_validos = ['consulta', 'sugerencia', 'error', 'otro'];

    if ($nombre === '' || mb_strlen($nombre) > 80) {
        $errores[] = 'El nombre es obligatorio y admite hasta 80 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Introduce un correo electrónico válido.';
    }

    if (!in_array($asunto, $asuntos_validos, true)) {
        $errores[] = 'Selecciona un asunto válido.';
    }

    if ($mensaje === '' || mb_strlen($mensaje) > 1000) {
        $errores[] = 'El mensaje es obligatorio y admite hasta 1000 caracteres.';
    }

    if (!$errores) {
        try {
            $pdo = db_conectar();

            $sentencia = $pdo->prepare(
                'INSERT INTO mensajes (nombre, email, asunto, mensaje)
                 VALUES (:nombre, :email, :asunto, :mensaje)'
            );
            $sentencia->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':asunto' => $asunto,
                ':mensaje' => $mensaje,
            ]);

            flash('exito', '¡Mensaje enviado! Te responderé lo antes posible.');
            header('Location: contacto.php');
            exit;
        } catch (PDOException) {
            $errores[] = 'No se pudo enviar el mensaje. Inténtalo de nuevo.';
        }
    }
}

$titulo = 'Contacto | LoginRegister';
$descripcion = 'Envíame tus dudas, sugerencias o comentarios sobre el proyecto LoginRegister.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Contacto</h1>
    <p>¿Alguna duda sobre el proyecto, una sugerencia o has encontrado un fallo? Escríbeme.</p>
</article>

<section class="panel panel--ancho revelar">
    <h2>Formulario de contacto</h2>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="contacto.php">
        <?= csrf_campo() ?>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" maxlength="80" autocomplete="name" required
               value="<?= e($_POST['nombre'] ?? '') ?>">

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= e($_POST['email'] ?? '') ?>">

        <label for="asunto">Asunto</label>
        <select id="asunto" name="asunto" required>
            <option value="" <?= !isset($_POST['asunto']) ? 'selected' : '' ?>>Elige una opción…</option>
            <option value="consulta" <?= ($_POST['asunto'] ?? '') === 'consulta' ? 'selected' : '' ?>>Consulta</option>
            <option value="sugerencia" <?= ($_POST['asunto'] ?? '') === 'sugerencia' ? 'selected' : '' ?>>Sugerencia</option>
            <option value="error" <?= ($_POST['asunto'] ?? '') === 'error' ? 'selected' : '' ?>>He encontrado un error</option>
            <option value="otro" <?= ($_POST['asunto'] ?? '') === 'otro' ? 'selected' : '' ?>>Otro</option>
        </select>

        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="5" maxlength="1000" required
                  placeholder="Cuéntame brevemente…"><?= e($_POST['mensaje'] ?? '') ?></textarea>
        <p class="contador"><output id="contador-mensaje">0 / 1000</output></p>

        <button type="submit" class="boton">Enviar mensaje</button>
    </form>
</section>

<aside class="panel revelar">
    <h2>Otras formas de encontrarme</h2>
    <address>
        <p>GitHub: <a href="https://github.com/tu-usuario" rel="noopener" target="_blank">github.com/tu-usuario</a></p>
        <p>Correo: <a href="mailto:tu-correo@ejemplo.com">tu-correo@ejemplo.com</a></p>
    </address>
</aside>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
