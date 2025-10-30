SELECT 
    c.idcliente,
    c.nome,
    c.cpf_cnpj,
    c.tipo_pessoa,
    c.email,
    c.ativo,
    COUNT(DISTINCT a.idassinatura) AS total_assinaturas,
    COUNT(DISTINCT CASE WHEN a.status = 'ativa' THEN a.idassinatura END) AS assinaturas_ativas,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE 0 END), 2) AS gasto_mensal,
    c.data_cadastro,
    MAX(a.data_inicio) AS ultima_assinatura
FROM clientes c
LEFT JOIN assinaturas a ON c.idcliente = a.idcliente
WHERE c.ativo = 1
GROUP BY c.idcliente
ORDER BY gasto_mensal DESC;