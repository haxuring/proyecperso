<?php

session_start();

$titulo = 'Inicio | LoginRegister';
$descripcion = 'Proyecto personal de autenticación con PHP y MySQL: registro seguro, inicio de sesión y panel privado.';

require_once __DIR__ . '/includes/header.php';
?>

<article>
    <h1>Sistema de login y registro con PHP y MySQL</h1>
    <p>Un proyecto personal, sencillo y seguro para practicar autenticación de usuarios: registro
        con contraseña cifrada, inicio de sesión protegido frente a inyección SQL y un panel privado.</p>

    <?php if (isset($_SESSION['usuario_id'])): ?>
    <p><a class="boton" href="dashboard.php">Ir a mi panel</a></p>
    <?php else: ?>
    <p>
        <a class="boton" href="register.php">Crear una cuenta</a>
        <a class="boton boton-secundario" href="login.php">Iniciar sesión</a>
    </p>
    <?php endif; ?>
</article>

<section>
    <h2>Características principales</h2>
    <ul>
        <li>Registro de usuarios con validación en el servidor.</li>
        <li>Contraseñas cifradas con <code>password_hash()</code>.</li>
        <li>Consultas preparadas con PDO para evitar inyección SQL.</li>
        <li>Panel privado accesible solo con sesión iniciada.</li>
    </ul>
</section>

<section>
    <h2>Tecnologías utilizadas</h2>
    <ul>
        <li>PHP 8 con PDO.</li>
        <li>MySQL / MariaDB.</li>
        <li>HTML5 semántico y CSS3 sin frameworks.</li>
    </ul>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
