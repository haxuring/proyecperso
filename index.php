<?php

session_start();

$titulo = 'Inicio | LoginRegister';
$descripcion = 'Proyecto personal de autenticación con PHP, MySQL y JavaScript: registro seguro, inicio de sesión y panel privado.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Sistema de login y registro con PHP y MySQL</h1>
    <p>Un proyecto personal para practicar autenticación de principio a fin: contraseñas cifradas,
        protección CSRF, modo oscuro y una interfaz responsive construida sin frameworks.</p>

    <div class="heroe-acciones">
        <?php if (isset($_SESSION['usuario_id'])): ?>
        <a class="boton" href="dashboard.php">Ir a mi panel</a>
        <a class="boton boton--secundario" href="perfil.php">Editar mi perfil</a>
        <?php else: ?>
        <a class="boton" href="register.php">Crear una cuenta</a>
        <a class="boton boton--secundario" href="login.php">Iniciar sesión</a>
        <?php endif; ?>
    </div>

    <ul class="chips">
        <li class="chip">Sin frameworks</li>
        <li class="chip">Contraseñas cifradas</li>
        <li class="chip">Protección CSRF</li>
        <li class="chip">Modo oscuro</li>
    </ul>
</article>

<section aria-labelledby="titulo-caracteristicas">
    <h2 id="titulo-caracteristicas" class="revelar">Características principales</h2>

    <ul class="tarjetas">
        <li class="tarjeta revelar">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <h3>Autenticación segura</h3>
            <p>Contraseñas cifradas con bcrypt, tokens CSRF en todos los formularios y bloqueo
                temporal tras intentos fallidos.</p>
        </li>

        <li class="tarjeta revelar">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4l3 3"/>
            </svg>
            <h3>Sesiones protegidas</h3>
            <p>Regeneración del identificador de sesión al entrar y opción «recuérdame» durante
                30 días.</p>
        </li>

        <li class="tarjeta revelar">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
            <h3>Tema claro y oscuro</h3>
            <p>Detecta la preferencia del sistema y recuerda tu elección entre visitas gracias
                a JavaScript.</p>
        </li>

        <li class="tarjeta revelar">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            <h3>Perfil editable</h3>
            <p>Cambia tu nombre, correo o contraseña desde el panel privado, verificando siempre
                la contraseña actual.</p>
        </li>
    </ul>
</section>

<section aria-labelledby="titulo-tecnologias">
    <h2 id="titulo-tecnologias" class="revelar">Tecnologías utilizadas</h2>

    <ul class="tarjetas">
        <li class="tarjeta revelar">
            <h3>PHP 8 + PDO</h3>
            <p>Lógica del servidor con sentencias preparadas en todas las consultas.</p>
        </li>
        <li class="tarjeta revelar">
            <h3>MySQL</h3>
            <p>Dos tablas relacionales: usuarios y mensajes de contacto.</p>
        </li>
        <li class="tarjeta revelar">
            <h3>JavaScript vanilla</h3>
            <p>Menú móvil, validaciones en vivo y avisos animados sin librerías externas.</p>
        </li>
        <li class="tarjeta revelar">
            <h3>HTML5 + CSS3</h3>
            <p>Marcado semántico, accesible y con tipografía fluida usando <code>clamp()</code>.</p>
        </li>
    </ul>
</section>

<section class="panel cta revelar">
    <h2>¿Quieres probarlo tú mismo?</h2>
    <p>Crea una cuenta gratuita en menos de un minuto o consulta cómo instalarlo
        desde cero leyendo el README del repositorio.</p>
    <div class="heroe-acciones">
        <a class="boton" href="register.php">Registrarme ahora</a>
        <a class="boton boton--secundario" href="acerca-de.php">Saber más</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
