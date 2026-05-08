<?php
require_once 'conexao.php';
$user = require_login();
$barbearia = user_barbearia((int) $user['id']);
if (!$barbearia) redirect('dashboard.php');
if (isset($_GET['cancelar'])) {
    $stmt = db()->prepare('UPDATE agendamentos SET status = "cancelado" WHERE id = ? AND barbearia_id = ?');
    $stmt->execute([(int) $_GET['cancelar'], $barbearia['id']]);
    flash('success', 'Agendamento cancelado.'); redirect('agendamentos.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data_agendamento'] ?? '';
    $hora = $_POST['hora_agendamento'] ?? '';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) && preg_match('/^\d{2}:\d{2}$/', $hora)) {
        $stmt = db()->prepare('INSERT INTO agendamentos (barbearia_id, cliente_nome, cliente_telefone, data_agendamento, hora_agendamento, status, observacoes) VALUES (?, "Indisponível", "", ?, ?, "indisponivel", ?)');
        $stmt->execute([$barbearia['id'], $data, $hora, trim($_POST['observacoes'] ?? 'Bloqueado pelo barbeiro')]);
        flash('success', 'Horário marcado como indisponível.'); redirect('agendamentos.php');
    }
    flash('error', 'Informe data e horário válidos.');
}
$stmt = db()->prepare('SELECT a.*, s.nome servico_nome FROM agendamentos a LEFT JOIN servicos s ON s.id = a.servico_id WHERE a.barbearia_id = ? ORDER BY a.data_agendamento DESC, a.hora_agendamento DESC');
$stmt->execute([$barbearia['id']]);
$agendamentos = $stmt->fetchAll();
page_header('Agendamentos | AgendaLocal', 'CRUD de agendamentos para cancelar reservas e marcar horários indisponíveis.');
?>
<section class="section"><div class="container">
    <?php render_flash(); ?><h1>Agendamentos</h1><p>Veja reservas, cancele quando necessário e bloqueie horários da agenda.</p>
    <div class="grid-2">
        <div class="card"><h3>Marcar horário como indisponível</h3><form class="form" method="post">
            <label>Data <input type="date" name="data_agendamento" required></label>
            <label>Horário <input type="time" name="hora_agendamento" required></label>
            <label>Motivo <textarea name="observacoes">Bloqueado pelo barbeiro</textarea></label>
            <button class="btn" type="submit">Bloquear horário</button>
        </form></div>
        <div class="table-wrap"><table><thead><tr><th>Cliente</th><th>Serviço</th><th>Data</th><th>Status</th><th>Ações</th></tr></thead><tbody>
            <?php foreach ($agendamentos as $item): ?><tr><td><?= e($item['cliente_nome']) ?><br><small><?= e($item['cliente_telefone']) ?></small></td><td><?= e($item['servico_nome'] ?: '-') ?></td><td><?= e(date('d/m/Y', strtotime($item['data_agendamento'])) . ' ' . substr($item['hora_agendamento'], 0, 5)) ?></td><td><span class="badge <?= $item['status'] === 'confirmado' ? 'badge-ok' : 'badge-off' ?>"><?= e($item['status']) ?></span></td><td><?php if ($item['status'] !== 'cancelado'): ?><a data-confirm="Cancelar este agendamento?" href="agendamentos.php?cancelar=<?= (int) $item['id'] ?>">Cancelar</a><?php endif; ?></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div>
</div></section>
<?php page_footer(); ?>
