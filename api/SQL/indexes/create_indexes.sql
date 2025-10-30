-- ============================================
-- ÍNDICES OTIMIZADOS - ORGANIZAAI API
-- Melhoria de performance em queries frequentes
-- ============================================

-- ============================================
-- ÍNDICES PARA CLIENTES
-- ============================================

-- Índice para buscas por CPF/CNPJ
CREATE INDEX idx_clientes_cpf_cnpj ON clientes(cpf_cnpj);

-- Índice para filtros por status
CREATE INDEX idx_clientes_ativo ON clientes(ativo);

-- Índice composto para listagens paginadas
CREATE INDEX idx_clientes_ativo_id ON clientes(ativo, idcliente);

-- Índice para busca por email
CREATE INDEX idx_clientes_email ON clientes(email);

-- ============================================
-- ÍNDICES PARA CLIENTES_ENDERECOS
-- ============================================

-- Índice para relacionamento com clientes
CREATE INDEX idx_clientes_enderecos_idcliente ON clientes_enderecos(idcliente);

-- Índice para endereço principal
CREATE INDEX idx_clientes_enderecos_principal ON clientes_enderecos(idcliente, principal);

-- ============================================
-- ÍNDICES PARA CLIENTES_CONTATOS
-- ============================================

-- Índice para relacionamento com clientes
CREATE INDEX idx_clientes_contatos_idcliente ON clientes_contatos(idcliente);

-- Índice para contato principal
CREATE INDEX idx_clientes_contatos_principal ON clientes_contatos(idcliente, principal);

-- ============================================
-- ÍNDICES PARA SISTEMAS
-- ============================================

-- Índice para status
CREATE INDEX idx_sistemas_ativo ON sistemas(ativo);

-- Índice para busca por nome
CREATE INDEX idx_sistemas_nome ON sistemas(nome);

-- Índice composto para listagens
CREATE INDEX idx_sistemas_ativo_id ON sistemas(ativo, idsistema);

-- ============================================
-- ÍNDICES PARA SISTEMAS_PLANOS
-- ============================================

-- Índice para sistema
CREATE INDEX idx_sistemas_planos_idsistema ON sistemas_planos(idsistema);

-- Índice para status
CREATE INDEX idx_sistemas_planos_ativo ON sistemas_planos(ativo);

-- Índice composto para busca rápida
CREATE INDEX idx_sistemas_planos_sistema_ativo ON sistemas_planos(idsistema, ativo);

-- ============================================
-- ÍNDICES PARA SISTEMAS_ADDONS
-- ============================================

-- Índice para sistema
CREATE INDEX idx_sistemas_addons_idsistema ON sistemas_addons(idsistema);

-- Índice para status
CREATE INDEX idx_sistemas_addons_ativo ON sistemas_addons(ativo);

-- Índice composto
CREATE INDEX idx_sistemas_addons_sistema_ativo ON sistemas_addons(idsistema, ativo);

-- ============================================
-- ÍNDICES PARA ASSINATURAS
-- ============================================

-- Índice para cliente
CREATE INDEX idx_assinaturas_idcliente ON assinaturas(idcliente);

-- Índice para sistema
CREATE INDEX idx_assinaturas_idsistema ON assinaturas(idsistema);

-- Índice para plano
CREATE INDEX idx_assinaturas_idplano ON assinaturas(idplano);

-- Índice para status
CREATE INDEX idx_assinaturas_status ON assinaturas(status);

-- Índice para data de vencimento (crítico para renovações)
CREATE INDEX idx_assinaturas_data_vencimento ON assinaturas(data_vencimento);

-- Índice composto para renovações
CREATE INDEX idx_assinaturas_status_vencimento ON assinaturas(status, data_vencimento);

-- Índice composto para cliente ativo
CREATE INDEX idx_assinaturas_idcliente_status ON assinaturas(idcliente, status);

-- ============================================
-- ÍNDICES PARA ASSINATURAS_ADDONS
-- ============================================

-- Índice para assinatura
CREATE INDEX idx_assinaturas_addons_idassinatura ON assinaturas_addons(idassinatura);

-- Índice para addon
CREATE INDEX idx_assinaturas_addons_idaddon ON assinaturas_addons(idaddon);

-- ============================================
-- ÍNDICES PARA PRECOS_HISTORICO
-- ============================================

-- Índice para tipo_entidade e id_entidade (crucial para auditoria)
CREATE INDEX idx_precos_historico_tipo_id ON precos_historico(tipo_entidade, id_entidade);

-- Índice para data de alteração (para relatórios por período)
CREATE INDEX idx_precos_historico_data_alteracao ON precos_historico(data_alteracao);

-- Índice composto para queries de auditoria
CREATE INDEX idx_precos_historico_tipo_id_data ON precos_historico(tipo_entidade, id_entidade, data_alteracao);

-- ============================================
-- CHAVES ESTRANGEIRAS COM ÍNDICES (já inclusos em constraints)
-- ============================================

-- NOTA: Chaves estrangeiras automaticamente criam índices no MySQL InnoDB
-- Abaixo estão documentadas para referência:
-- - assinaturas.idcliente -> clientes.idcliente
-- - assinaturas.idsistema -> sistemas.idsistema
-- - assinaturas.idplano -> sistemas_planos.idplano
-- - assinaturas_addons.idassinatura -> assinaturas.idassinatura
-- - assinaturas_addons.idaddon -> sistemas_addons.idaddon
-- - sistemas_planos.idsistema -> sistemas.idsistema
-- - sistemas_addons.idsistema -> sistemas.idsistema
-- - clientes_enderecos.idcliente -> clientes.idcliente
-- - clientes_contatos.idcliente -> clientes.idcliente

-- ============================================
-- ÍNDICES COVERING (SELECT sem acesso ao table)
-- ============================================

-- Para listagem de planos com preços
CREATE INDEX idx_sistemas_planos_covering 
ON sistemas_planos(idsistema, ativo, idplano, nome, preco);

-- Para listagem de add-ons com preços
CREATE INDEX idx_sistemas_addons_covering 
ON sistemas_addons(idsistema, ativo, idaddon, nome, preco);

-- Para listagem de assinaturas ativas
CREATE INDEX idx_assinaturas_covering 
ON assinaturas(idcliente, status, idassinatura, data_vencimento, preco_negociado);

-- ============================================
-- DICAS DE OTIMIZAÇÃO
-- ============================================

-- 1. ANALYZE TABLE após criação de índices
-- ANALYZE TABLE clientes;
-- ANALYZE TABLE sistemas;
-- ANALYZE TABLE assinaturas;
-- ANALYZE TABLE precos_historico;

-- 2. Para verificar índices criados:
-- SHOW INDEXES FROM clientes;
-- SHOW INDEXES FROM assinaturas;

-- 3. Para otimizar queries:
-- EXPLAIN SELECT ... -- Use para verificar uso de índices

-- 4. Para manutenção periódica:
-- OPTIMIZE TABLE clientes;
-- OPTIMIZE TABLE assinaturas;
-- OPTIMIZE TABLE precos_historico;

-- ============================================
-- TOTAL DE ÍNDICES CRIADOS: 31
-- ============================================
