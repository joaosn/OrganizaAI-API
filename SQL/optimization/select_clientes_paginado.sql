-- Listagem paginada de clientes (otimizada com LIMIT/OFFSET)
-- Uso: Database::switchParams(['offset' => 0, 'limit' => 20], 'optimization/select_clientes_paginado', true)

SELECT 
    c.idcliente,
    c.tipo_pessoa,
    c.nome,
    c.nome_fantasia,
    c.cpf_cnpj,
    c.email,
    c.telefone,
    c.ativo,
    c.data_cadastro,
    (SELECT COUNT(*) FROM assinaturas WHERE idcliente = c.idcliente AND status = 'ativa') as assinaturas_ativas
FROM clientes c
WHERE c.ativo = 1
ORDER BY c.data_cadastro DESC
LIMIT :limit OFFSET :offset;
