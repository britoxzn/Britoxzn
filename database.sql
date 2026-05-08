CREATE DATABASE IF NOT EXISTS agendalocal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agendalocal;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE barbearias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  nome VARCHAR(160) NOT NULL,
  slug VARCHAR(190) NOT NULL UNIQUE,
  cidade VARCHAR(120) NOT NULL,
  endereco VARCHAR(190) DEFAULT '',
  whatsapp VARCHAR(40) DEFAULT '',
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_barbearias_usuarios FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE servicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  barbearia_id INT NOT NULL,
  nome VARCHAR(140) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0,
  duracao_minutos INT NOT NULL DEFAULT 30,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_servicos_barbearias FOREIGN KEY (barbearia_id) REFERENCES barbearias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  barbearia_id INT NOT NULL,
  servico_id INT NULL,
  cliente_nome VARCHAR(140) NOT NULL,
  cliente_telefone VARCHAR(40) NOT NULL,
  data_agendamento DATE NOT NULL,
  hora_agendamento TIME NOT NULL,
  status ENUM('confirmado','cancelado','indisponivel') NOT NULL DEFAULT 'confirmado',
  observacoes TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_agenda_horario (barbearia_id, data_agendamento, hora_agendamento, status),
  CONSTRAINT fk_agendamentos_barbearias FOREIGN KEY (barbearia_id) REFERENCES barbearias(id) ON DELETE CASCADE,
  CONSTRAINT fk_agendamentos_servicos FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  titulo VARCHAR(190) NOT NULL,
  meta_title VARCHAR(190) NOT NULL,
  meta_description VARCHAR(255) NOT NULL,
  conteudo TEXT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE artigos_blog (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL UNIQUE,
  resumo VARCHAR(255) NOT NULL,
  meta_title VARCHAR(190) NOT NULL,
  meta_description VARCHAR(255) NOT NULL,
  conteudo TEXT NOT NULL,
  publicado_em DATE NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (nome, email, senha) VALUES
('Barbeiro Demo', 'demo@agendalocal.com', '$2y$12$HgGOoGIa23Iy1SN8a2Us7eP45b0psk5qieg5YZCsXRifi13gjD82i');

INSERT INTO barbearias (usuario_id, nome, slug, cidade, endereco, whatsapp, descricao) VALUES
(1, 'Barbearia Corte Fino', 'barbearia-corte-fino-brasilia', 'Brasília', 'Asa Norte, Brasília - DF', '(61) 99999-0000', 'Cortes modernos, barba alinhada e atendimento com hora marcada em Brasília.');

INSERT INTO servicos (barbearia_id, nome, descricao, preco, duracao_minutos, ativo) VALUES
(1, 'Corte masculino', 'Corte clássico ou moderno com acabamento profissional.', 45.00, 40, 1),
(1, 'Barba completa', 'Toalha quente, navalha e finalização.', 35.00, 30, 1),
(1, 'Corte + barba', 'Combo completo para renovar o visual.', 75.00, 70, 1);

INSERT INTO agendamentos (barbearia_id, servico_id, cliente_nome, cliente_telefone, data_agendamento, hora_agendamento, status, observacoes) VALUES
(1, 1, 'Cliente Exemplo', '(61) 98888-0000', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 'confirmado', 'Preferência por degradê baixo'),
(1, NULL, 'Indisponível', '', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '10:00:00', 'indisponivel', 'Horário bloqueado pelo barbeiro');

INSERT INTO cidades (nome, slug, titulo, meta_title, meta_description, conteudo) VALUES
('Brasília', 'brasilia', 'Sistema de agendamento para barbeiros em Brasília', 'Sistema de agendamento para barbeiros em Brasília | AgendaLocal', 'Agenda online para barbeiros em Brasília com reservas rápidas, serviços, preços e horários disponíveis.', 'Barbeiros em Brasília precisam aparecer no momento em que o cliente pesquisa por corte, barba ou horário livre perto da região. O AgendaLocal ajuda a criar uma página clara com serviços, preços e botão de reserva.\n\nCom uma agenda online, a barbearia reduz mensagens repetidas, evita conflito de horários e melhora a experiência de quem quer reservar pelo celular. A página pode ser trabalhada com termos locais como sistema de agendamento para barbeiros em Brasília e agenda online para barbearia no DF.'),
('Taguatinga', 'taguatinga', 'Agenda online para barbearia em Taguatinga', 'Agenda online para barbearia em Taguatinga | AgendaLocal', 'Crie uma página local para barbearia em Taguatinga receber reservas online e organizar clientes.', 'Em Taguatinga, muitos clientes buscam praticidade para marcar corte e barba sem ligação. Uma página local com horários disponíveis, WhatsApp e serviços aumenta a confiança e facilita a reserva.\n\nO AgendaLocal entrega uma estrutura objetiva para divulgar a barbearia, organizar a agenda de clientes e criar conteúdo com foco em buscas locais.'),
('Ceilândia', 'ceilandia', 'Agendamento para barbeiro em Ceilândia', 'Agendamento para barbeiro em Ceilândia | AgendaLocal', 'Sistema simples para barbeiros em Ceilândia divulgarem horários e receberem reservas online.', 'Uma barbearia em Ceilândia pode usar SEO Local para aparecer quando alguém procura corte masculino, barba ou agenda online na região. A combinação de página pública, serviços e horários disponíveis reduz atrito na reserva.');

INSERT INTO artigos_blog (titulo, slug, resumo, meta_title, meta_description, conteudo, publicado_em) VALUES
('Como organizar agenda de clientes na barbearia', 'como-organizar-agenda-clientes-barbearia', 'Aprenda um processo simples para evitar conflito de horários e melhorar o atendimento.', 'Como organizar agenda de clientes na barbearia | AgendaLocal', 'Guia prático para barbeiros organizarem agenda de clientes, horários, confirmações e reservas online.', 'Organizar a agenda começa com uma lista clara de serviços e duração média de cada atendimento. Depois, separe horários de trabalho, pausas e encaixes.\n\nUse uma agenda online para centralizar pedidos e manter nome, telefone, serviço, data e horário no mesmo lugar. Isso diminui mensagens perdidas e facilita a confirmação pelo WhatsApp.\n\nRevise a agenda no começo e no fim do dia para identificar horários vagos e clientes que precisam de lembrete.', CURDATE()),
('Melhor sistema de agendamento para barbeiros', 'melhor-sistema-agendamento-barbeiros', 'Veja o que um barbeiro local realmente precisa em um sistema de agenda.', 'Melhor sistema de agendamento para barbeiros | AgendaLocal', 'Entenda quais recursos importam em um sistema de agendamento para barbeiros e barbearias locais.', 'O melhor sistema de agendamento para barbeiros não precisa ser gigante. Ele precisa ser rápido, fácil de usar no celular e focado na rotina da barbearia.\n\nRecursos essenciais incluem página pública, cadastro de serviços, reservas com WhatsApp, cancelamento e bloqueio de horários. Para crescer, páginas de SEO Local ajudam a atrair clientes da cidade ou bairro.', CURDATE()),
('Como evitar faltas de clientes na barbearia', 'como-evitar-faltas-clientes-barbearia', 'Boas práticas para reduzir no-show e manter a agenda cheia.', 'Como evitar faltas de clientes na barbearia | AgendaLocal', 'Dicas para evitar faltas de clientes na barbearia usando confirmação, lembretes e agenda online.', 'Faltas acontecem menos quando o cliente recebe confirmação clara do horário. Salve telefone, serviço escolhido, data e hora da reserva.\n\nEnvie lembrete pelo WhatsApp algumas horas antes e facilite o cancelamento antecipado. Assim, se o cliente não puder ir, você abre o horário para outra pessoa.\n\nTambém vale criar políticas simples para atrasos em horários de maior movimento.', CURDATE());
