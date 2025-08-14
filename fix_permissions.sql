-- Script para corrigir o problema de permissões
-- Remove o campo 'role' conflitante da tabela users

-- 1. Verificar se o campo 'role' existe e removê-lo
ALTER TABLE users DROP COLUMN IF EXISTS role;

-- 2. Limpar dados antigos de permissões se necessário
DELETE FROM model_has_roles;
DELETE FROM model_has_permissions;

-- 3. Recriar as permissões básicas
INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES
('acessar_orcamentos', 'web', NOW(), NOW()),
('acessar_financeiro', 'web', NOW(), NOW()),
('acessar_clientes', 'web', NOW(), NOW()),
('acessar_autores', 'web', NOW(), NOW()),
('acessar_portfolio', 'web', NOW(), NOW()),
('gerenciar_usuarios', 'web', NOW(), NOW());

-- 4. Recriar as roles básicas
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) VALUES
('Admin', 'web', NOW(), NOW()),
('User', 'web', NOW(), NOW());

-- 5. Atribuir todas as permissões para a role Admin
INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id 
FROM permissions p 
CROSS JOIN roles r 
WHERE r.name = 'Admin';

-- 6. Atribuir permissões específicas para a role User
INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id 
FROM permissions p 
CROSS JOIN roles r 
WHERE r.name = 'User' 
AND p.name IN ('acessar_orcamentos', 'acessar_financeiro', 'acessar_clientes', 'acessar_autores', 'acessar_portfolio');

-- 7. Atribuir role Admin ao primeiro usuário
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\Models\\User', u.id
FROM roles r
CROSS JOIN users u
WHERE r.name = 'Admin'
AND u.id = (SELECT MIN(id) FROM users);

SELECT 'Script executado com sucesso!' as resultado;