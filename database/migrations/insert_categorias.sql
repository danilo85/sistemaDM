-- Inserir categorias de receitas
INSERT INTO categorias (nome, descricao, tipo, created_at, updated_at) VALUES
('Salário', 'Salário mensal e benefícios trabalhistas', 'receita', NOW(), NOW()),
('Freelance', 'Trabalhos freelancer e projetos independentes', 'receita', NOW(), NOW()),
('Vendas', 'Receitas de vendas de produtos ou serviços', 'receita', NOW(), NOW()),
('Investimentos', 'Rendimentos de investimentos e aplicações', 'receita', NOW(), NOW()),
('Aluguel Recebido', 'Receitas de aluguel de imóveis', 'receita', NOW(), NOW()),
('Comissões', 'Comissões e bonificações recebidas', 'receita', NOW(), NOW()),
('Outros Rendimentos', 'Outras fontes de receita diversas', 'receita', NOW(), NOW());

-- Inserir categorias de despesas
INSERT INTO categorias (nome, descricao, tipo, created_at, updated_at) VALUES
('Alimentação', 'Gastos com alimentação, supermercado e restaurantes', 'despesa', NOW(), NOW()),
('Transporte', 'Combustível, transporte público e manutenção veicular', 'despesa', NOW(), NOW()),
('Moradia', 'Aluguel, financiamento, condomínio e contas da casa', 'despesa', NOW(), NOW()),
('Saúde', 'Plano de saúde, medicamentos e consultas médicas', 'despesa', NOW(), NOW()),
('Educação', 'Cursos, livros, mensalidades e material escolar', 'despesa', NOW(), NOW()),
('Lazer', 'Entretenimento, viagens e atividades recreativas', 'despesa', NOW(), NOW()),
('Vestuário', 'Roupas, calçados e acessórios', 'despesa', NOW(), NOW()),
('Outros Gastos', 'Despesas diversas não categorizadas', 'despesa', NOW(), NOW());