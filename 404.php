<?php

session_start();

$titulo = 'Página no encontrada | LoginRegister';
$descripcion = 'La página que buscas no existe o ha sido movida.';

require_once __DIR__ . '/includes/header.php';
?>

<article class="panel pagina-error revelar">
    <h1>404</h1>
    <p>Vaya… la página que buscas no existe o ha sido movida de sitio.</p>
    <p>Puedes volver a la <a href="index.php">página de inicio</a> o escribirme desde
        la <a href="contacto.php">página de contacto</a>.</p>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
