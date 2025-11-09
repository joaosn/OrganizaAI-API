-- Busca assinaturas vencidas ou próximas do vencimento para renovação
-- Parâmetros: :dias_antecedencia (ex: 5 dias antes do vencimento)
SELECT 
    a.idassinatura,
    a.idcliente,
    a.idplano,
    a.preco_negociado,
    a.data_inicio,
    a.data_vencimento,
    a.status,
    a.aliquota_imposto,
    c.nome as cliente_nome,
    sp.descricao as plano_descricao,
    sp.preco as preco_plano_base,
    DATEDIFF(a.data_vencimento, CURDATE()) as dias_restantes,
    (SELECT SUM(preco) FROM assinaturas_addons WHERE idassinatura = a.idassinatura AND ativo = 1) as total_addons
FROM assinaturas a
INNER JOIN clientes c ON a.idcliente = c.idcliente
INNER JOIN sistemas_planos sp ON a.idplano = sp.idplano
WHERE a.status IN ('ativa', 'suspensa')
  AND DATEDIFF(a.data_vencimento, CURDATE()) <= :dias_antecedencia
  AND DATEDIFF(a.data_vencimento, CURDATE()) >= 0
ORDER BY a.data_vencimento ASC;
