<?php
require_once 'conexao.php';
$slug = $_GET['slug'] ?? '';
if ($slug === '' && isset($_SERVER['REQUEST_URI']) && preg_match('#/(?:barbeiros|agendamento-barbeiro)-([a-z0-9-]+)#', $_SERVER['REQUEST_URI'], $m)) {
    $slug = $m[1];
}
if ($slug) {
    $stmt = db()->prepare('SELECT * FROM cidades WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $cidade = $stmt->fetch();
    if ($cidade) {
        page_header($cidade['meta_title'], $cidade['meta_description']);
        ?>
        <section class="section"><div class="container">
            <span class="eyebrow">SEO Local</span><h1><?= e($cidade['titulo']) ?></h1>
            <article class="card article-content"><?= nl2br(e($cidade['conteudo'])) ?></article>
            <div class="section-title"><h2>URL amigável sugerida</h2><p>/barbeiros/<?= e($cidade['slug']) ?> ou /agendamento-barbeiro-<?= e($cidade['slug']) ?></p></div>
            <a class="btn" href="cadastro.php">Criar agenda para minha barbearia</a>
        </div></section>
        <?php page_footer(); exit;
    }
}
$stmt = db()->query('SELECT * FROM cidades ORDER BY nome');
$cidades = $stmt->fetchAll();
page_header('Páginas SEO Local para barbeiros | AgendaLocal', 'Gere páginas otimizadas por cidade para sistema de agendamento de barbeiros e barbearias locais.');
?>
<section class="section"><div class="container">
    <span class="eyebrow">SEO Local</span><h1>Páginas por cidade para barbearias</h1><p>Modelo de landing pages com título, descrição, conteúdo local e URLs amigáveis.</p>
    <div class="grid-3">
        <?php foreach ($cidades as $cidade): ?><article class="card"><h3><?= e($cidade['titulo']) ?></h3><p><?= e($cidade['meta_description']) ?></p><a class="btn btn-small" href="cidades.php?slug=<?= e($cidade['slug']) ?>">Ver página</a><p><small>/barbeiros/<?= e($cidade['slug']) ?></small></p></article><?php endforeach; ?>
    </div>
</div></section>
<?php page_footer(); ?>
