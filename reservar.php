<?php
require_once 'conexao.php';
$barbearia = get_demo_or_first_barbearia(isset($_GET['barbearia']) ? (int) $_GET['barbearia'] : (int) ($_POST['barbearia_id'] ?? 0));
if (!$barbearia) redirect('barbearia.php');
$stmt = db()->prepare('SELECT * FROM servicos WHERE barbearia_id = ? AND ativo = 1 ORDER BY nome');
$stmt->execute([$barbearia['id']]);
$servicos = $stmt->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['cliente_nome'] ?? '');
    $telefone = trim($_POST['cliente_telefone'] ?? '');
    $servicoId = (int) ($_POST['servico_id'] ?? 0);
    $data = $_POST['data_agendamento'] ?? '';
    $hora = $_POST['hora_agendamento'] ?? '';
    if ($nome === '' || $telefone === '' || !$servicoId || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || !preg_match('/^\d{2}:\d{2}$/', $hora)) {
        $errors[] = 'Preencha todos os campos corretamente.';
    }
    $stmt = db()->prepare('SELECT id FROM agendamentos WHERE barbearia_id = ? AND data_agendamento = ? AND hora_agendamento = ? AND status IN ("confirmado", "indisponivel")');
    $stmt->execute([$barbearia['id'], $data, $hora]);
    if ($stmt->fetch()) $errors[] = 'Este horário não está mais disponível.';
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO agendamentos (barbearia_id, servico_id, cliente_nome, cliente_telefone, data_agendamento, hora_agendamento, status, observacoes) VALUES (?, ?, ?, ?, ?, ?, "confirmado", ?)');
        $stmt->execute([$barbearia['id'], $servicoId, $nome, $telefone, $data, $hora, trim($_POST['observacoes'] ?? '')]);
        flash('success', 'Reserva criada com sucesso. A barbearia poderá confirmar pelo WhatsApp.');
        redirect('barbearia.php?id=' . (int) $barbearia['id']);
    }
}
page_header('Reservar horário | ' . $barbearia['nome'], 'Faça sua reserva online na ' . $barbearia['nome'] . ' com nome, WhatsApp, serviço, data e horário.');
?>
<section class="section"><div class="container two-col">
    <div><span class="eyebrow">Reserva rápida</span><h1>Reserve na <?= e($barbearia['nome']) ?></h1><p>Informe seus dados para a barbearia registrar o horário.</p></div>
    <div class="card">
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endforeach; ?>
        <form class="form" method="post">
            <input type="hidden" name="barbearia_id" value="<?= (int) $barbearia['id'] ?>">
            <label>Nome <input name="cliente_nome" required value="<?= e($_POST['cliente_nome'] ?? '') ?>"></label>
            <label>Telefone/WhatsApp <input name="cliente_telefone" required value="<?= e($_POST['cliente_telefone'] ?? '') ?>"></label>
            <label>Serviço <select name="servico_id" required><option value="">Selecione</option><?php foreach ($servicos as $servico): ?><option value="<?= (int) $servico['id'] ?>"><?= e($servico['nome']) ?> - R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?></option><?php endforeach; ?></select></label>
            <label>Data <input type="date" name="data_agendamento" required value="<?= e($_GET['data'] ?? $_POST['data_agendamento'] ?? date('Y-m-d')) ?>"></label>
            <label>Horário <input type="time" name="hora_agendamento" required value="<?= e($_GET['hora'] ?? $_POST['hora_agendamento'] ?? '09:00') ?>"></label>
            <label>Observações <textarea name="observacoes"><?= e($_POST['observacoes'] ?? '') ?></textarea></label>
            <button class="btn" type="submit">Confirmar reserva</button>
        </form>
    </div>
</div></section>
<?php page_footer(); ?>
