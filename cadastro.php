<?php
require_once 'conexao.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $barbeariaNome = trim($_POST['barbearia_nome'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');

    if ($nome === '' || $barbeariaNome === '' || $cidade === '') $errors[] = 'Preencha nome, barbearia e cidade.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Informe um e-mail válido.';
    if (strlen($senha) < 6) $errors[] = 'A senha deve ter no mínimo 6 caracteres.';

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Este e-mail já está cadastrado.';
        } else {
            db()->beginTransaction();
            $stmt = db()->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
            $usuarioId = (int) db()->lastInsertId();
            $slug = slugify($barbeariaNome . '-' . $cidade);
            $stmt = db()->prepare('INSERT INTO barbearias (usuario_id, nome, slug, cidade, endereco, whatsapp, descricao) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$usuarioId, $barbeariaNome, $slug, $cidade, '', $whatsapp, 'Agenda online da ' . $barbeariaNome]);
            db()->commit();
            $_SESSION['usuario_id'] = $usuarioId;
            flash('success', 'Conta criada com sucesso. Cadastre seus serviços para começar.');
            redirect('dashboard.php');
        }
    }
}
page_header('Cadastro | AgendaLocal', 'Crie uma conta grátis para cadastrar sua barbearia e receber agendamentos online.');
?>
<section class="section"><div class="container two-col">
    <div><span class="eyebrow">Comece grátis</span><h1>Cadastre sua barbearia</h1><p>Depois do cadastro você poderá adicionar serviços, ver reservas e divulgar sua página pública.</p></div>
    <div class="card">
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endforeach; ?>
        <form class="form" method="post">
            <label>Seu nome <input name="nome" required value="<?= e($_POST['nome'] ?? '') ?>"></label>
            <label>E-mail <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
            <label>Senha <input type="password" name="senha" minlength="6" required></label>
            <label>Nome da barbearia <input name="barbearia_nome" required value="<?= e($_POST['barbearia_nome'] ?? '') ?>"></label>
            <label>Cidade <input name="cidade" required value="<?= e($_POST['cidade'] ?? '') ?>"></label>
            <label>WhatsApp <input name="whatsapp" value="<?= e($_POST['whatsapp'] ?? '') ?>"></label>
            <button class="btn" type="submit">Criar conta</button>
        </form>
    </div>
</div></section>
<?php page_footer(); ?>
