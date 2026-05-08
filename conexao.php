<?php
// AgendaLocal - conexão, sessão e funções compartilhadas.
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'agendalocal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_URL', ''); // Em produção, use algo como: https://seudominio.com

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
    $text = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($text));
    return trim((string) $text, '-') ?: 'pagina';
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function current_user(): ?array
{
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT * FROM usuarios WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['usuario_id']]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        redirect('login.php');
    }

    return $user;
}

function user_barbearia(int $usuarioId): ?array
{
    $stmt = db()->prepare('SELECT * FROM barbearias WHERE usuario_id = ? LIMIT 1');
    $stmt->execute([$usuarioId]);
    $barbearia = $stmt->fetch();

    return $barbearia ?: null;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function render_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }

    foreach ($_SESSION['flash'] as $item) {
        echo '<div class="alert alert-' . e($item['type']) . '">' . e($item['message']) . '</div>';
    }

    unset($_SESSION['flash']);
}

function page_header(string $title, string $description = ''): void
{
    $description = $description ?: 'AgendaLocal: agenda online simples e otimizada para barbeiros e barbearias locais.';
    $user = current_user();
    ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?= e((APP_URL ?: '') . strtok($_SERVER['REQUEST_URI'] ?? '', '?')) ?>">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="nav container">
        <a class="logo" href="index.php">Agenda<span>Local</span></a>
        <button class="menu-toggle" data-menu-toggle aria-label="Abrir menu">☰</button>
        <div class="nav-links" data-nav-links>
            <a href="index.php">Início</a>
            <a href="cidades.php">SEO Local</a>
            <a href="blog.php">Blog</a>
            <a href="barbearia.php">Barbearia demo</a>
            <?php if ($user): ?>
                <a href="dashboard.php">Painel</a>
                <a class="btn btn-small" href="login.php?sair=1">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a class="btn btn-small" href="cadastro.php">Começar grátis</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
    <?php
}

function page_footer(): void
{
    ?>
</main>
<footer class="footer">
    <div class="container footer-grid">
        <div>
            <strong>AgendaLocal</strong>
            <p>SaaS simples para barbeiros venderem mais com agenda online e SEO Local.</p>
        </div>
        <div>
            <a href="cidades.php">Páginas por cidade</a>
            <a href="blog.php">Artigos</a>
            <a href="cadastro.php">Criar conta</a>
        </div>
    </div>
</footer>
<script src="assets/js/app.js"></script>
</body>
</html>
<?php
}

function get_demo_or_first_barbearia(?int $id = null): ?array
{
    if ($id) {
        $stmt = db()->prepare('SELECT * FROM barbearias WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
    } else {
        $stmt = db()->query('SELECT * FROM barbearias ORDER BY id LIMIT 1');
    }

    $barbearia = $stmt->fetch();
    return $barbearia ?: null;
}
