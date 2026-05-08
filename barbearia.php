<?php
require_once 'conexao.php';
$barbearia = get_demo_or_first_barbearia(isset($_GET['id']) ? (int) $_GET['id'] : null);
if (!$barbearia) { page_header('Barbearia não encontrada | AgendaLocal'); echo '<section class="section"><div class="container"><h1>Nenhuma barbearia cadastrada</h1><p>Importe o database.sql ou crie uma conta.</p></div></section>'; page_footer(); exit; }
$stmt = db()->prepare('SELECT * FROM servicos WHERE barbearia_id = ? AND ativo = 1 ORDER BY preco');
$stmt->execute([$barbearia['id']]);
$servicos = $stmt->fetchAll();
$stmt = db()->prepare('SELECT data_agendamento, hora_agendamento FROM agendamentos WHERE barbearia_id = ? AND status IN ("confirmado", "indisponivel") AND data_agendamento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)');
$stmt->execute([$barbearia['id']]);
$ocupados = array_map(fn($r) => $r['data_agendamento'] . ' ' . substr($r['hora_agendamento'], 0, 5), $stmt->fetchAll());
$horarios = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];
page_header($barbearia['nome'] . ' | Agendamento online em ' . $barbearia['cidade'], 'Reserve horário online na ' . $barbearia['nome'] . ' em ' . $barbearia['cidade'] . '. Veja serviços, preços e horários disponíveis.');
?>
<section class="public-header"><div class="container">
    <span class="eyebrow">Agenda online em <?= e($barbearia['cidade']) ?></span>
    <h1><?= e($barbearia['nome']) ?></h1><p><?= e($barbearia['descricao']) ?></p>
    <p><?= e($barbearia['endereco']) ?> · WhatsApp: <?= e($barbearia['whatsapp']) ?></p>
</div></section>
<section class="section"><div class="container grid-2">
    <div><h2>Serviços e preços</h2><div class="grid-2">
        <?php foreach ($servicos as $servico): ?><article class="card"><h3><?= e($servico['nome']) ?></h3><p><?= e($servico['descricao']) ?></p><div class="price">R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?></div><small><?= (int) $servico['duracao_minutos'] ?> minutos</small></article><?php endforeach; ?>
    </div></div>
    <div class="card"><h2>Horários disponíveis</h2><p>Escolha um horário livre e finalize a reserva em poucos segundos.</p>
        <div class="calendar-mock">
        <?php for ($d = 0; $d < 7; $d++): $data = date('Y-m-d', strtotime("+$d day")); foreach ($horarios as $hora): if (!in_array($data . ' ' . $hora, $ocupados, true)): ?>
            <div class="slot"><span><?= e(date('d/m', strtotime($data)) . ' às ' . $hora) ?></span><a class="btn btn-small" href="reservar.php?barbearia=<?= (int) $barbearia['id'] ?>&data=<?= e($data) ?>&hora=<?= e($hora) ?>">Reservar</a></div>
        <?php endif; endforeach; endfor; ?>
        </div>
    </div>
</div></section>
<?php page_footer(); ?>
