<?php
require_once 'conexao.php';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT * FROM artigos_blog WHERE slug = ? LIMIT 1');
$stmt->execute([$slug]);
$artigo = $stmt->fetch();
if (!$artigo) { redirect('blog.php'); }
page_header($artigo['meta_title'], $artigo['meta_description']);
?>
<section class="section"><div class="container">
    <span class="eyebrow">Blog AgendaLocal</span><h1><?= e($artigo['titulo']) ?></h1><p><?= e($artigo['resumo']) ?></p>
    <article class="card article-content"><?= nl2br(e($artigo['conteudo'])) ?></article>
</div></section>
<?php page_footer(); ?>
