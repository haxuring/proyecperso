<?php

session_start();
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

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
                $_SESSION['usuario_id'] = (int) $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];

                header('Location: dashboard.php');
                exit;
            }

            $errores[] = 'Correo o contraseña incorrectos.';
        } catch (PDOException) {
            $errores[] = 'No se pudo iniciar sesión. Inténtalo de nuevo.';
        }
    }
}

$titulo = 'Iniciar sesión | LoginRegister';
$descripcion = 'Accede a tu cuenta de LoginRegister con tu correo electrónico y contraseña.';

require_once __DIR__ . '/includes/header.php';
?>

<section>
    <h1>Iniciar sesión</h1>

    <?php if ($errores): ?>
    <ul class="errores" role="alert">
        <?php foreach ($errores as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="120" autocomplete="email" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>

        <button type="submit">Entrar</button>
    </form>
</section>

<aside>
    <h2>¿Aún no tienes cuenta?</h2>
    <p>Regístrate gratis desde <a href="register.php">este enlace</a>.</p>
</aside>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
