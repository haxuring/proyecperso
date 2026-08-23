<?php

session_start();
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    'INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :password_hash)'
                );
                $sentencia->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $pdo->lastInsertId();
                $_SESSION['usuario_nombre'] = $nombre;

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

<section>
    <h1>Crear una cuenta</h1>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" maxlength="80" autocomplete="name" required
               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="password">Contraseña (mínimo 8 caracteres)</label>
        <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>

        <label for="password_confirm">Repite la contraseña</label>
        <input type="password" id="password_confirm" name="password_confirm" minlength="8" autocomplete="new-password"
               required>

        <button type="submit">Registrarme</button>
    </form>
</section>

<aside>
    <h2>¿Ya tienes una cuenta?</h2>
    <p>Inicia sesión desde <a href="login.php">este enlace</a>.</p>
</aside>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
