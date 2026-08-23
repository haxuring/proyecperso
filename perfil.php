<?php

session_start();
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validar($_POST['csrf_token'] ?? null)) {
        $errores[] = 'Sesión de seguridad caducada. Vuelve a enviar el formulario.';
    }

    $accion = $_POST['accion'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');

    try {
        $pdo = db_conectar();

        if ($accion === 'guardar_perfil') {
            if ($nombre === '' || mb_strlen($nombre) > 80) {
                $errores[] = 'El nombre es obligatorio y admite hasta 80 caracteres.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 120) {
                $errores[] = 'Introduce un correo electrónico válido.';
            }

            if (!$errores) {
                $sentencia = $pdo->prepare(
                    'SELECT id FROM usuarios WHERE email = :email AND id <> :id'
                );
                $sentencia->execute([':email' => $email, ':id' => $_SESSION['usuario_id']]);

                if ($sentencia->fetch()) {
                    $errores[] = 'Ese correo ya está en uso por otra cuenta.';
                } else {
                    $sentencia = $pdo->prepare(
                        'UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id'
                    );
                    $sentencia->execute([
                        ':nombre' => $nombre,
                        ':email' => $email,
                        ':id' => $_SESSION['usuario_id'],
                    ]);

                    $_SESSION['usuario_nombre'] = $nombre;
                    flash('exito', 'Perfil actualizado correctamente.');
                    header('Location: perfil.php');
                    exit;
                }
            }
        } elseif ($accion === 'cambiar_password') {
            $password_actual = $_POST['password_actual'] ?? '';
            $password_nueva = $_POST['password_nueva'] ?? '';
            $confirmacion = $_POST['password_confirm'] ?? '';

            if (strlen($password_nueva) < 8) {
                $errores[] = 'La nueva contraseña debe tener al menos 8 caracteres.';
            }

            if ($password_nueva !== $confirmacion) {
                $errores[] = 'Las contraseñas nuevas no coinciden.';
            }

            if (!$errores) {
                $sentencia = $pdo->prepare(
                    'SELECT password_hash FROM usuarios WHERE id = :id'
                );
                $sentencia->execute([':id' => $_SESSION['usuario_id']]);
                $registro = $sentencia->fetch();

                if (!$registro || !password_verify($password_actual, $registro['password_hash'])) {
                    $errores[] = 'La contraseña actual no es correcta.';
                } else {
                    $sentencia = $pdo->prepare(
                        'UPDATE usuarios SET password_hash = :hash WHERE id = :id'
                    );
                    $sentencia->execute([
                        ':hash' => password_hash($password_nueva, PASSWORD_DEFAULT),
                        ':id' => $_SESSION['usuario_id'],
                    ]);

                    flash('exito', 'Contraseña cambiada correctamente.');
                    header('Location: perfil.php');
                    exit;
                }
            }
        }
    } catch (PDOException) {
        $errores[] = 'No se pudieron guardar los cambios. Inténtalo de nuevo.';
    }
}

try {
    if (!isset($pdo)) {
        $pdo = db_conectar();
    }

    $sentencia = $pdo->prepare('SELECT nombre, email FROM usuarios WHERE id = :id');
    $sentencia->execute([':id' => $_SESSION['usuario_id']]);
    $usuario = $sentencia->fetch();
} catch (PDOException) {
    $usuario = null;
}

if (!$usuario) {
    header('Location: logout.php');
    exit;
}

$titulo = 'Mi perfil | LoginRegister';
$descripcion = 'Edita los datos de tu cuenta en LoginRegister.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Mi perfil</h1>
    <p>Modifica tus datos personales o cambia tu contraseña. Los cambios surten efecto al instante.</p>
</article>

<section class="panel panel--ancho revelar">
    <h2>Datos personales</h2>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="perfil.php">
        <?= csrf_campo() ?>
        <input type="hidden" name="accion" value="guardar_perfil">

        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" maxlength="80" autocomplete="name" required
               value="<?= e($_POST['nombre'] ?? $usuario['nombre']) ?>">

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= e($_POST['email'] ?? $usuario['email']) ?>">

        <button type="submit">Guardar cambios</button>
    </form>
</section>

<section class="panel panel--ancho revelar" aria-labelledby="titulo-password">
    <h2 id="titulo-password">Cambiar contraseña</h2>

    <form method="post" action="perfil.php">
        <?= csrf_campo() ?>
        <input type="hidden" name="accion" value="cambiar_password">

        <label for="password_actual">Contraseña actual</label>
        <p class="campo-password">
            <input type="password" id="password_actual" name="password_actual"
                   autocomplete="current-password" required>
            <button type="button" class="alternar-visibilidad" data-alternar-password="password_actual"
                    aria-pressed="false" aria-label="Mostrar contraseña">
                <svg class="icono-ojo" viewBox="0 0 24 24" width="20" height="20" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="icono-ojo-tachado oculto" viewBox="0 0 24 24" width="20" height="20"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            </button>
        </p>

        <label for="password_nueva">Nueva contraseña (mínimo 8 caracteres)</label>
        <input type="password" id="password_nueva" name="password_nueva" minlength="8"
               autocomplete="new-password" required>

        <label for="password_confirm">Repite la nueva contraseña</label>
        <input type="password" id="password_confirm" name="password_confirm" minlength="8"
               autocomplete="new-password" required>

        <button type="submit">Cambiar contraseña</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
