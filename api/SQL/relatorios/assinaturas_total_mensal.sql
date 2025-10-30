SELECT 
    a.idcliente,
    c.nome AS cliente_nome,
    MONTH(a.data_inicio) AS mes,
    YEAR(a.data_inicio) AS ano,
    COUNT(DISTINCT a.idassinatura) AS total_assinaturas,
    SUM(ROUND(a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100), 2)) AS receita_base,
    SUM(COALESCE(aa_total.custo_addons, 0)) AS receita_addons,
    SUM(ROUND(a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100), 2)) + 
    SUM(COALESCE(aa_total.custo_addons, 0)) AS receita_total
FROM assinaturas a
LEFT JOIN clientes c ON a.idcliente = c.idcliente
LEFT JOIN (
    SELECT 
        idassinatura,
        ROUND(SUM(COALESCE(preco_unitario, 0) * quantidade), 2) AS custo_addons
    FROM assinaturas_addons
    GROUP BY idassinatura
) aa_total ON a.idassinatura = aa_total.idassinatura
WHERE a.status = 'ativa'
  AND (:data_inicio IS NULL OR a.data_inicio >= :data_inicio)
  AND (:data_fim IS NULL OR a.data_inicio <= :data_fim)
GROUP BY a.idcliente, MONTH(a.data_inicio), YEAR(a.data_inicio)
ORDER BY ano DESC, mes DESC, cliente_nome ASC;