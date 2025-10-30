-- Listagem de assinaturas ativas (cached query)
-- Otimizada com índices covering
-- Uso: Database::switchParams(['idcliente' => 1], 'optimization/select_assinaturas_ativas', true)

SELECT 
    a.idassinatura,
    a.idcliente,
    a.idsistema,
    a.idplano,
    a.preco_negociado,
    a.aliquota_imposto,
    a.data_inicio,
    a.data_vencimento,
    a.status,
    s.nome as sistema_nome,
    sp.nome as plano_nome,
    DATEDIFF(a.data_vencimento, CURDATE()) as dias_para_vencer
FROM assinaturas a
INNER JOIN sistemas s ON a.idsistema = s.idsistema
INNER JOIN sistemas_planos sp ON a.idplano = sp.idplano
WHERE a.idcliente = :idcliente
  AND a.status = 'ativa'
ORDER BY a.data_vencimento ASC;
