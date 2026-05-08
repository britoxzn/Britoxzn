# AgendaLocal

AgendaLocal é um SaaS simples de agendamento para barbeiros e barbearias locais. O projeto foi criado em **PHP + MySQL**, com frontend em **HTML, CSS e JavaScript**, pensando em hospedagens compartilhadas como InfinityFree.

## Funcionalidades

- Página inicial moderna e responsiva.
- Página pública da barbearia com serviços, preços, horários disponíveis e botão de reserva.
- Reserva com nome do cliente, telefone/WhatsApp, serviço, data e horário.
- Login e cadastro simples para barbeiros.
- Painel do barbeiro com resumo de agenda.
- CRUD de serviços.
- CRUD básico de agendamentos: listagem, cancelamento e bloqueio de horários indisponíveis.
- Páginas de SEO Local por cidade, com meta title, meta description, conteúdo e URLs amigáveis.
- Blog simples com artigos otimizados para barbearias.

## Estrutura de arquivos

Coloque todos os arquivos na pasta pública da hospedagem, normalmente `htdocs`, `public_html` ou equivalente.

```text
.
├── .htaccess
├── index.php
├── login.php
├── cadastro.php
├── dashboard.php
├── barbearia.php
├── reservar.php
├── servicos.php
├── agendamentos.php
├── cidades.php
├── blog.php
├── artigo.php
├── conexao.php
├── database.sql
└── assets
    ├── css
    │   └── style.css
    └── js
        └── app.js
```

## Como instalar

1. Crie um banco MySQL chamado `agendalocal` no painel da hospedagem.
2. Importe o arquivo `database.sql` pelo phpMyAdmin.
3. Edite `conexao.php` com os dados reais do banco:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
   - `APP_URL` (opcional, mas recomendado em produção)
4. Envie todos os arquivos para a pasta pública da hospedagem.
5. Acesse `index.php` no navegador.

## Acesso demo

Após importar o banco, use:

- E-mail: `demo@agendalocal.com`
- Senha: `123456`

## URLs importantes

- `index.php`: landing page do SaaS.
- `barbearia.php`: página pública demo da barbearia.
- `reservar.php`: formulário de reserva.
- `login.php`: login do barbeiro.
- `cadastro.php`: cadastro de conta e barbearia.
- `dashboard.php`: painel do barbeiro.
- `servicos.php`: cadastro, edição e exclusão de serviços.
- `agendamentos.php`: listagem, cancelamento e bloqueio de horários.
- `cidades.php`: listagem e exibição de páginas SEO Local.
- `blog.php`: listagem de artigos.
- `artigo.php`: página de artigo.

## URLs amigáveis

O arquivo `.htaccess` ativa exemplos como:

- `/barbeiros/brasilia`
- `/agendamento-barbeiro-taguatinga`
- `/blog/como-organizar-agenda-clientes-barbearia`

Caso a hospedagem não aceite `mod_rewrite`, use as versões com query string:

- `cidades.php?slug=brasilia`
- `cidades.php?slug=taguatinga`
- `artigo.php?slug=como-organizar-agenda-clientes-barbearia`

## Observações de produção

- Troque a senha demo após instalar.
- Use HTTPS na hospedagem.
- Configure `APP_URL` em `conexao.php` para melhorar as tags canônicas.
- Para um SaaS comercial real, adicione recuperação de senha, envio automático de lembretes, painel administrativo e proteção CSRF completa.
