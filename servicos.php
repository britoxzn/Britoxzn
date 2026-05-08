<?php
require_once 'conexao.php';
$user = require_login();
$barbearia = user_barbearia((int) $user['id']);
if (!$barbearia) redirect('dashboard.php');
$edit = null;
if (isset($_GET['editar'])) {
    $stmt = db()->prepare('SELECT * FROM servicos WHERE id = ? AND barbearia_id = ?');
    $stmt->execute([(int) $_GET['editar'], $barbearia['id']]);
    $edit = $stmt->fetch();
}
if (isset($_GET['excluir'])) {
    $stmt = db()->prepare('DELETE FROM servicos WHERE id = ? AND barbearia_id = ?');
    $stmt->execute([(int) $_GET['excluir'], $barbearia['id']]);
    flash('success', 'Serviço excluído.'); redirect('servicos.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = (float) ($_POST['preco'] ?? 0);
    $duracao = (int) ($_POST['duracao_minutos'] ?? 30);
    $descricao = trim($_POST['descricao'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    if ($nome === '' || $preco < 0 || $duracao < 10) {
        flash('error', 'Informe nome, preço e duração válida.');
    } elseif (!empty($_POST['id'])) {
        $stmt = db()->prepare('UPDATE servicos SET nome=?, descricao=?, preco=?, duracao_minutos=?, ativo=? WHERE id=? AND barbearia_id=?');
        $stmt->execute([$nome, $descricao, $preco, $duracao, $ativo, (int) $_POST['id'], $barbearia['id']]);
        flash('success', 'Serviço atualizado.'); redirect('servicos.php');
    } else {
        $stmt = db()->prepare('INSERT INTO servicos (barbearia_id, nome, descricao, preco, duracao_minutos, ativo) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$barbearia['id'], $nome, $descricao, $preco, $duracao, $ativo]);
        flash('success', 'Serviço cadastrado.'); redirect('servicos.php');
    }
}
$stmt = db()->prepare('SELECT * FROM servicos WHERE barbearia_id = ? ORDER BY nome');
$stmt->execute([$barbearia['id']]);
$servicos = $stmt->fetchAll();
page_header('Serviços | AgendaLocal', 'CRUD de serviços da barbearia com preços, duração e status ativo.');
?>
<section class="section"><div class="container">
    <?php render_flash(); ?><h1>Serviços</h1><p>Cadastre cortes, barba, combos e preços para aparecerem na página pública.</p>
    <div class="grid-2">
        <div class="card"><h3><?= $edit ? 'Editar serviço' : 'Novo serviço' ?></h3><form class="form" method="post">
            <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
            <label>Nome <input name="nome" required value="<?= e($edit['nome'] ?? '') ?>"></label>
            <label>Descrição <textarea name="descricao"><?= e($edit['descricao'] ?? '') ?></textarea></label>
            <label>Preço <input type="number" step="0.01" min="0" name="preco" required value="<?= e($edit['preco'] ?? '') ?>"></label>
            <label>Duração em minutos <input type="number" min="10" name="duracao_minutos" required value="<?= e($edit['duracao_minutos'] ?? 30) ?>"></label>
            <label><span><input type="checkbox" name="ativo" <?= (!$edit || (int) $edit['ativo'] === 1) ? 'checked' : '' ?>> Ativo</span></label>
            <button class="btn" type="submit">Salvar serviço</button>
        </form></div>
        <div class="table-wrap"><table><thead><tr><th>Serviço</th><th>Preço</th><th>Status</th><th>Ações</th></tr></thead><tbody>
            <?php foreach ($servicos as $servico): ?><tr><td><?= e($servico['nome']) ?><br><small><?= e($servico['duracao_minutos']) ?> min</small></td><td>R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?></td><td><span class="badge <?= $servico['ativo'] ? 'badge-ok' : 'badge-off' ?>"><?= $servico['ativo'] ? 'ativo' : 'inativo' ?></span></td><td><a href="servicos.php?editar=<?= (int) $servico['id'] ?>">Editar</a> · <a data-confirm="Excluir serviço?" href="servicos.php?excluir=<?= (int) $servico['id'] ?>">Excluir</a></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div>
</div></section>
<?php page_footer(); ?>
