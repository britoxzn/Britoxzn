<?php
require_once 'conexao.php';
$user = require_login();
$barbearia = user_barbearia((int) $user['id']);
if (!$barbearia) { flash('error', 'Cadastre uma barbearia para continuar.'); redirect('cadastro.php'); }
$stmt = db()->prepare('SELECT COUNT(*) total FROM agendamentos WHERE barbearia_id = ? AND status = "confirmado"');
$stmt->execute([$barbearia['id']]);
$totalAgendamentos = (int) $stmt->fetch()['total'];
$stmt = db()->prepare('SELECT COUNT(*) total FROM servicos WHERE barbearia_id = ? AND ativo = 1');
$stmt->execute([$barbearia['id']]);
$totalServicos = (int) $stmt->fetch()['total'];
$stmt = db()->prepare('SELECT a.*, s.nome servico_nome FROM agendamentos a LEFT JOIN servicos s ON s.id = a.servico_id WHERE a.barbearia_id = ? ORDER BY a.data_agendamento, a.hora_agendamento LIMIT 6');
$stmt->execute([$barbearia['id']]);
$proximos = $stmt->fetchAll();
page_header('Dashboard | AgendaLocal', 'Painel simples do barbeiro para acompanhar reservas, serviços e bloqueios de agenda.');
?>
<section class="section"><div class="container">
    <?php render_flash(); ?>
    <span class="eyebrow">Painel do barbeiro</span><h1><?= e($barbearia['nome']) ?></h1><p>Gerencie sua agenda online e compartilhe sua página pública.</p>
    <div class="hero-actions"><a class="btn" href="servicos.php">Cadastrar serviços</a><a class="btn btn-secondary" href="agendamentos.php">Ver agendamentos</a><a class="btn btn-outline" href="barbearia.php?id=<?= (int) $barbearia['id'] ?>">Página pública</a></div>
    <div class="grid-3 section">
        <div class="kpi"><h3><?= $totalAgendamentos ?></h3><p>Agendamentos confirmados</p></div>
        <div class="kpi"><h3><?= $totalServicos ?></h3><p>Serviços ativos</p></div>
        <div class="kpi"><h3><?= e($barbearia['cidade']) ?></h3><p>Cidade principal para SEO Local</p></div>
    </div>
    <h2>Próximos horários</h2>
    <div class="table-wrap"><table><thead><tr><th>Cliente</th><th>Serviço</th><th>Data</th><th>Status</th></tr></thead><tbody>
        <?php foreach ($proximos as $item): ?><tr><td><?= e($item['cliente_nome']) ?><br><small><?= e($item['cliente_telefone']) ?></small></td><td><?= e($item['servico_nome'] ?: 'Horário bloqueado') ?></td><td><?= e(date('d/m/Y', strtotime($item['data_agendamento'])) . ' ' . substr($item['hora_agendamento'], 0, 5)) ?></td><td><span class="badge <?= $item['status'] === 'confirmado' ? 'badge-ok' : 'badge-off' ?>"><?= e($item['status']) ?></span></td></tr><?php endforeach; ?>
        <?php if (!$proximos): ?><tr><td colspan="4">Nenhum agendamento encontrado.</td></tr><?php endif; ?>
    </tbody></table></div>
</div></section>
<?php page_footer(); ?>
