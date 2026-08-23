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

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmacion = $_POST['password_confirm'] ?? '';

    if ($nombre === '' || mb_strlen($nombre) > 80) {
        $errores[] = 'El nombre es obligatorio y admite hasta 80 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 120) {
        $errores[] = 'Introduce un correo electrónico válido.';
    }

    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    if ($password !== $confirmacion) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (!$errores) {
        try {
            $pdo = db_conectar();

            $sentencia = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
            $sentencia->execute([':email' => $email]);

            if ($sentencia->fetch()) {
                $errores[] = 'Ese correo electrónico ya está registrado.';
            } else {
                $sentencia = $pdo->prepare(
                    'INSERT INTO usuarios (nombre, email, password_hash)
                     VALUES (:nombre, :email, :password_hash)'
                );
                $sentencia->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $pdo->lastInsertId();
                $_SESSION['usuario_nombre'] = $nombre;

                flash('exito', "¡Cuenta creada! Bienvenido/a, {$nombre}.");
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException) {
            $errores[] = 'No se pudo completar el registro. Inténtalo de nuevo.';
        }
    }
}

$titulo = 'Crear cuenta | LoginRegister';
$descripcion = 'Crea tu cuenta gratis en LoginRegister: registro rápido y seguro con PHP y MySQL.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="panel panel--ancho revelar">
    <h1>Crear una cuenta</h1>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="register.php" id="form-registro" novalidate>
        <?= csrf_campo() ?>

        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" maxlength="80" autocomplete="name" required
               value="<?= e($_POST['nombre'] ?? '') ?>">

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= e($_POST['email'] ?? '') ?>">

        <label for="password">Contraseña (mínimo 8 caracteres)</label>
        <p class="campo-password">
            <input type="password" id="password" name="password" minlength="8"
                   autocomplete="new-password" required>
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

        <progress id="medidor-fuerza" max="4" value="0"></progress>
        <p id="texto-fuerza" class="fuerza-texto" data-nivel="0" aria-live="polite">
            Seguridad de la contraseña: —
        </p>

        <label for="password_confirm">Repite la contraseña</label>
        <p class="campo-password">
            <input type="password" id="password_confirm" name="password_confirm" minlength="8"
                   autocomplete="new-password" required aria-describedby="error-coincidencia">
            <button type="button" class="alternar-visibilidad" data-alternar-password="password_confirm"
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
        <p id="error-coincidencia" class="error-campo" aria-live="polite"></p>

        <button type="submit">Registrarme</button>
    </form>
</section>

<aside class="panel revelar">
    <h2>¿Ya tienes una cuenta?</h2>
    <p>Inicia sesión desde <a href="login.php">este enlace</a>. También puedes echar un vistazo
        a las <a href="faq.php">preguntas frecuentes</a>.</p>
</aside>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
