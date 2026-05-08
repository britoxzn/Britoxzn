<?php
require_once 'conexao.php';
if (isset($_GET['sair'])) {
    session_destroy();
    redirect('index.php');
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $stmt = db()->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = (int) $user['id'];
        redirect('dashboard.php');
    }
    $erro = 'E-mail ou senha inválidos.';
}
page_header('Login | AgendaLocal', 'Acesse o painel da sua barbearia no AgendaLocal.');
?>
<section class="section"><div class="container two-col">
    <div><span class="eyebrow">Painel do barbeiro</span><h1>Entre para gerenciar sua agenda.</h1><p>Usuário demo após importar o banco: demo@agendalocal.com / 123456</p></div>
    <div class="card">
        <?php if ($erro): ?><div class="alert alert-error"><?= e($erro) ?></div><?php endif; ?>
        <form class="form" method="post">
            <label>E-mail <input type="email" name="email" required></label>
            <label>Senha <input type="password" name="senha" required></label>
            <button class="btn" type="submit">Entrar</button>
        </form>
    </div>
</div></section>
<?php page_footer(); ?>
