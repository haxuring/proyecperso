<?php

session_start();

$titulo = 'Acerca de | LoginRegister';
$descripcion = 'Conoce el propósito, los objetivos y la hoja de ruta del proyecto LoginRegister.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Sobre este proyecto</h1>
    <p>LoginRegister nació como ejercicio de aprendizaje: construir un sistema de autenticación
        completo desde cero, entendiendo cada pieza en lugar de copiar y pegar.</p>

    <ul class="chips">
        <li class="chip">PHP 8</li>
        <li class="chip">MySQL</li>
        <li class="chip">JavaScript</li>
        <li class="chip">CSS moderno</li>
    </ul>
</article>

<section class="panel revelar">
    <h2>Objetivos del proyecto</h2>
    <ul>
        <li>Practicar PHP nativo con PDO, sin frameworks que oculten el funcionamiento interno.</li>
        <li>Aplicar buenas prácticas de seguridad desde el primer día: hash de contraseñas,
            sentencias preparadas, tokens CSRF y protección contra fuerza bruta.</li>
        <li>Escribir HTML semántico y accesible, con una única jerarquía de encabezados por página.</li>
        <li>Explorar CSS moderno: variables personalizadas, modo oscuro, rejillas fluidas y
            animaciones respetando las preferencias del usuario.</li>
        <li>Mantener un proyecto real publicado en GitHub, con README claro y commits ordenados.</li>
    </ul>
</section>

<section class="panel revelar">
    <h2>Tecnologías y decisiones técnicas</h2>
    <dl>
        <dt><strong>PHP + PDO</strong></dt>
        <dd>Todas las consultas usan sentencias preparadas; las contraseñas se cifran con
            <code>password_hash()</code>.</dd>

        <dt><strong>MySQL</strong></dt>
        <dd>Dos tablas: usuarios y mensajes de contacto, con índice único en el correo.</dd>

        <dt><strong>JavaScript vanilla</strong></dt>
        <dd>Menú responsive, tema claro/oscuro persistente, medidor de contraseña y avisos
            flotantes; sin ninguna librería externa.</dd>

        <dt><strong>CSS3</strong></dt>
        <dd>Variables personalizadas como sistema de diseño, <code>clamp()</code> para tipografía
            fluida y soporte de <code>prefers-reduced-motion</code>.</dd>
    </dl>
</section>

<section class="panel revelar">
    <h2>Hoja de ruta</h2>
    <ol class="hitos">
        <li><strong>v1 —</strong> Registro, login y panel privado.</li>
        <li><strong>v2 —</strong> Perfil editable, contacto, tema oscuro y mejoras de seguridad.</li>
        <li><strong>v3 (planeado) —</strong> Verificación por correo, recuperación de contraseña
            y API JSON para consumir el sistema desde otras apps.</li>
    </ol>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
