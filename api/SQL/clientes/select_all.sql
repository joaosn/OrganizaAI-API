SELECT 
    c.idcliente,
    c.tipo_pessoa,
    c.nome,
    c.nome_fantasia,
    c.cpf_cnpj,
    c.ie_rg,
    c.im,
    c.email,
    c.telefone,
    c.data_cadastro,
    c.ativo,
    COUNT(DISTINCT ce.idendereco) as total_enderecos,
    COUNT(DISTINCT cc.idcontato) as total_contatos,
    COUNT(DISTINCT a.idassinatura) as total_assinaturas
FROM clientes c
LEFT JOIN clientes_enderecos ce ON ce.idcliente = c.idcliente
LEFT JOIN clientes_contatos cc ON cc.idcliente = c.idcliente  
LEFT JOIN assinaturas a ON a.idcliente = c.idcliente AND a.status = 'ativa'
WHERE (:ativo IS NULL OR c.ativo = :ativo)
  AND (:tipo_pessoa IS NULL OR c.tipo_pessoa = :tipo_pessoa)
GROUP BY c.idcliente
ORDER BY c.nome
LIMIT :limit OFFSET :offset;