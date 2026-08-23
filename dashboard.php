<?php

session_start();
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = db_conectar();

    $sentencia = $pdo->prepare(
        'SELECT nombre, email, creado_en, ultimo_acceso FROM usuarios WHERE id = :id'
    );
    $sentencia->execute([':id' => $_SESSION['usuario_id']]);
    $usuario = $sentencia->fetch();
} catch (PDOException) {
    $usuario = null;
}

if (!$usuario) {
    header('Location: logout.php');
    exit;
}

$_SESSION['usuario_nombre'] = $usuario['nombre'];
$dias_miembro = (int) floor((time() - strtotime($usuario['creado_en'])) / 86400);

$titulo = 'Mi panel | LoginRegister';
$descripcion = 'Panel privado del usuario en LoginRegister.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Hola, <?= e($usuario['nombre']) ?></h1>
    <p>Bienvenido/a a tu área privada. Esta página solo es visible con la sesión iniciada.</p>

    <div class="heroe-acciones">
        <a class="boton" href="perfil.php">Editar mi perfil</a>
        <a class="boton boton--secundario" href="logout.php">Cerrar sesión</a>
    </div>
</article>

<section aria-labelledby="titulo-datos">
    <h2 id="titulo-datos" class="revelar">Tu cuenta</h2>

    <ul class="estadisticas">
        <li class="revelar">
            <span class="estadistica-dato"><?= e($usuario['email']) ?></span>
            <span class="estadistica-etiqueta">Correo electrónico</span>
        </li>
        <li class="revelar">
            <span class="estadistica-dato"><?= fecha_legible($usuario['creado_en']) ?></span>
            <span class="estadistica-etiqueta">Miembro desde (hace <?= $dias_miembro ?> día/s)</span>
        </li>
        <li class="revelar">
            <span class="estadistica-dato"><?= fecha_legible($usuario['ultimo_acceso']) ?></span>
            <span class="estadistica-etiqueta">Último inicio de sesión</span>
        </li>
    </ul>
</section>

<section class="panel revelar">
    <h2>Accesos rápidos</h2>
    <p>Desde tu perfil puedes cambiar el nombre, el correo o la contraseña. Si necesitas
        comunicarte conmigo, usa el formulario de contacto.</p>
    <div class="heroe-acciones heroe-acciones--inicio">
        <a class="boton boton--secundario" href="perfil.php">Ir a mi perfil</a>
        <a class="boton boton--secundario" href="contacto.php">Contactar</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
