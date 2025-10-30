SELECT 
    a.idassinatura,
    a.idcliente,
    a.idsistema,
    c.nome AS cliente_nome,
    s.nome AS sistema_nome,
    a.ciclo_cobranca,
    a.dia_vencimento,
    a.data_inicio,
    a.data_fim,
    a.status,
    a.preco_sem_imposto,
    a.aliquota_imposto_percent,
    ROUND(a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100), 2) AS preco_com_imposto,
    COUNT(DISTINCT aa.idaddon) AS total_addons,
    ROUND(SUM(COALESCE(aa.preco_unitario, 0) * aa.quantidade), 2) AS custo_addons
FROM assinaturas a
LEFT JOIN clientes c ON a.idcliente = c.idcliente
LEFT JOIN sistemas s ON a.idsistema = s.idsistema
LEFT JOIN assinaturas_addons aa ON a.idassinatura = aa.idassinatura
WHERE (:status IS NULL OR a.status = :status)
  AND (:idcliente IS NULL OR a.idcliente = :idcliente)
  AND (:idsistema IS NULL OR a.idsistema = :idsistema)
GROUP BY a.idassinatura
ORDER BY a.data_inicio DESC;