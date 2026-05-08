<?php
require_once 'conexao.php';
page_header('AgendaLocal | Agenda online para barbeiros com SEO Local', 'Sistema de agendamento simples para barbearias locais com reservas online, painel do barbeiro e páginas otimizadas por cidade.');
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">SaaS enxuto para barbearias locais</span>
            <h1>Agenda online para barbeiros venderem mais no bairro.</h1>
            <p>O AgendaLocal combina reservas rápidas, painel simples e páginas SEO por cidade para sua barbearia aparecer quando clientes procuram horários perto de casa.</p>
            <div class="hero-actions">
                <a class="btn" href="cadastro.php">Criar minha agenda</a>
                <a class="btn btn-outline" href="barbearia.php">Ver página pública</a>
            </div>
        </div>
        <div class="card card-visual">
            <span class="eyebrow">Hoje</span>
            <h3>Barbearia Corte Fino</h3>
            <div class="calendar-mock">
                <div class="slot"><span>09:00 · Corte masculino</span><strong>Livre</strong></div>
                <div class="slot"><span>10:30 · Barba completa</span><strong>Livre</strong></div>
                <div class="slot"><span>14:00 · Corte + barba</span><span class="badge badge-off">Reservado</span></div>
                <div class="slot"><span>16:00 · Degradê</span><strong>Livre</strong></div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Feito para barbeiros, não para empresas gigantes.</h2>
            <p>Cadastre serviços, receba pedidos com WhatsApp, bloqueie horários e crie páginas locais como “agendamento para barbeiro em Brasília”.</p>
        </div>
        <div class="grid-3">
            <article class="card"><h3>Reservas em minutos</h3><p>Cliente escolhe serviço, data, horário e deixa nome e telefone para confirmação.</p></article>
            <article class="card"><h3>SEO Local pronto</h3><p>Páginas por cidade com título, descrição, conteúdo e URLs amigáveis para ranquear no Google.</p></article>
            <article class="card"><h3>Painel simples</h3><p>O barbeiro vê agendamentos, cancela reservas, bloqueia horários e gerencia serviços.</p></article>
        </div>
    </div>
</section>
<section class="section">
    <div class="container two-col">
        <div class="card">
            <h2>Exemplos de páginas locais</h2>
            <p>Use termos que seus clientes realmente pesquisam:</p>
            <ul>
                <li>Sistema de agendamento para barbeiros em Brasília</li>
                <li>Agenda online para barbearia em Taguatinga</li>
                <li>Como organizar agenda de clientes em uma barbearia</li>
            </ul>
            <a class="btn" href="cidades.php">Ver páginas SEO</a>
        </div>
        <div class="grid-2">
            <div class="kpi"><h3>+ reservas</h3><p>Menos mensagens soltas e mais horários confirmados.</p></div>
            <div class="kpi"><h3>Menos faltas</h3><p>Contato do cliente salvo para lembretes via WhatsApp.</p></div>
            <div class="kpi"><h3>Mais buscas</h3><p>Conteúdo local para cidade, bairro e intenção.</p></div>
            <div class="kpi"><h3>Hospedagem simples</h3><p>PHP + MySQL para subir em hospedagem compartilhada.</p></div>
        </div>
    </div>
</section>
<?php page_footer(); ?>
