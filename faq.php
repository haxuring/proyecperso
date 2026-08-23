<?php

session_start();

$titulo = 'Preguntas frecuentes | LoginRegister';
$descripcion = 'Respuestas a las dudas más habituales sobre el funcionamiento y la seguridad del proyecto.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="heroe revelar">
    <h1>Preguntas frecuentes</h1>
    <p>Las dudas más comunes sobre este proyecto, respondidas de forma directa.</p>
</article>

<section class="panel revelar" aria-labelledby="titulo-seguridad">
    <h2 id="titulo-seguridad">Seguridad</h2>

    <details>
        <summary>¿Cómo se almacenan las contraseñas?</summary>
        <p>Nunca se guardan en texto plano: se cifran con <code>password_hash()</code> usando
            bcrypt, y se comprueban con <code>password_verify()</code> en el inicio de sesión.</p>
    </details>

    <details>
        <summary>¿Qué protege al formulario de ataques CSRF?</summary>
        <p>Todos los formularios incluyen un token aleatorio generado en la sesión que se valida
            con <code>hash_equals()</code> antes de procesar cualquier dato.</p>
    </details>

    <details>
        <summary>¿Existe protección contra fuerza bruta en el login?</summary>
        <p>Sí. Tras 5 intentos fallidos, la cuenta de sesión queda bloqueada durante 5 minutos
            y no se aceptan más intentos hasta que transcurra ese tiempo.</p>
    </details>
</section>

<section class="panel revelar" aria-labelledby="titulo-uso">
    <h2 id="titulo-uso">Uso del proyecto</h2>

    <details>
        <summary>¿Puedo usarlo en producción tal cual está?</summary>
        <p>Está pensado como proyecto de aprendizaje. Antes de producción se recomienda añadir
            verificación por correo, HTTPS obligatorio y limitación de intentos persistente en
            la base de datos.</p>
    </details>

    <details>
        <summary>¿Dónde cambio las credenciales de la base de datos?</summary>
        <p>Copia <code>config/database.example.php</code> a <code>config/database.php</code> y
            edita ahí tus valores. Ese archivo real está excluido por <code>.gitignore</code>.</p>
    </details>

    <details>
        <summary>¿Por qué no usa ningún framework ni librería?</summary>
        <p>El objetivo es aprender cómo funciona todo por dentro: sesiones, hashing, PDO,
            accesibilidad y JavaScript nativo. Sin capas intermedias que lo oculten.</p>
    </details>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
