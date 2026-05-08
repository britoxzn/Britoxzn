<?php
require_once 'conexao.php';
$stmt = db()->query('SELECT * FROM artigos_blog ORDER BY publicado_em DESC, id DESC');
$artigos = $stmt->fetchAll();
page_header('Blog para barbeiros | AgendaLocal', 'Artigos sobre agenda de clientes, agendamento online, faltas e organização de barbearias.');
?>
<section class="section"><div class="container">
    <span class="eyebrow">Conteúdo para crescer</span><h1>Blog AgendaLocal</h1><p>Guias simples para barbeiros organizarem agenda, reduzirem faltas e atraírem clientes locais.</p>
    <div class="grid-3">
        <?php foreach ($artigos as $artigo): ?><article class="card"><h3><?= e($artigo['titulo']) ?></h3><p><?= e($artigo['resumo']) ?></p><a class="btn btn-small" href="artigo.php?slug=<?= e($artigo['slug']) ?>">Ler artigo</a></article><?php endforeach; ?>
    </div>
</div></section>
<?php page_footer(); ?>
