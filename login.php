<?php

session_start();
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validar($_POST['csrf_token'] ?? null)) {
        $errores[] = 'Sesión de seguridad caducada. Vuelve a enviar el formulario.';
    }

    $bloqueado_hasta = $_SESSION['bloqueado_hasta'] ?? 0;

    if (time() < $bloqueado_hasta) {
        $minutos = (int) ceil(($bloqueado_hasta - time()) / 60);
        $errores[] = "Demasiados intentos fallidos. Espera {$minutos} minuto(s) antes de reintentarlo.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $recordar = isset($_POST['recordar']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Introduce un correo electrónico válido.';
        }

        if ($password === '') {
            $errores[] = 'Introduce tu contraseña.';
        }

        if (!$errores) {
            try {
                $pdo = db_conectar();

                $sentencia = $pdo->prepare(
                    'SELECT id, nombre, password_hash FROM usuarios WHERE email = :email'
                );
                $sentencia->execute([':email' => $email]);
                $usuario = $sentencia->fetch();

                if ($usuario && password_verify($password, $usuario['password_hash'])) {
                    session_regenerate_id(true);

                    if ($recordar) {
                        setcookie(session_name(), session_id(), [
                            'expires' => time() + 60 * 60 * 24 * 30,
                            'path' => '/',
                            'httponly' => true,
                            'samesite' => 'Lax',
                        ]);
                    }

                    $sentencia = $pdo->prepare(
                        'UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id'
                    );
                    $sentencia->execute([':id' => $usuario['id']]);

                    unset($_SESSION['intentos'], $_SESSION['bloqueado_hasta']);

                    $_SESSION['usuario_id'] = (int) $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];

                    flash('exito', "¡Hola de nuevo, {$usuario['nombre']}!");
                    header('Location: dashboard.php');
                    exit;
                }

                $_SESSION['intentos'] = ($_SESSION['intentos'] ?? 0) + 1;

                if ($_SESSION['intentos'] >= 5) {
                    $_SESSION['bloqueado_hasta'] = time() + 300;
                    unset($_SESSION['intentos']);
                }

                $errores[] = 'Correo o contraseña incorrectos.';
            } catch (PDOException) {
                $errores[] = 'No se pudo iniciar sesión. Inténtalo de nuevo.';
            }
        }
    }
}

$titulo = 'Iniciar sesión | LoginRegister';
$descripcion = 'Accede a tu cuenta de LoginRegister con tu correo electrónico y contraseña.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="panel panel--ancho revelar">
    <h1>Iniciar sesión</h1>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="login.php">
        <?= csrf_campo() ?>

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= e($_POST['email'] ?? '') ?>">

        <label for="password">Contraseña</label>
        <p class="campo-password">
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
            <button type="button" class="alternar-visibilidad" data-alternar-password="password"
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

        <p class="campo-check">
            <input type="checkbox" id="recordar" name="recordar">
            <label for="recordar">Recuérdame durante 30 días</label>
        </p>

        <button type="submit">Entrar</button>
    </form>
</section>

<aside class="panel revelar">
    <h2>¿Aún no tienes cuenta?</h2>
    <p>Regístrate gratis desde <a href="register.php">este enlace</a> en menos de un minuto.</p>
</aside>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
