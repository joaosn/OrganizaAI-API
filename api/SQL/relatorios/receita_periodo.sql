SELECT 
    COALESCE(MONTH(a.data_inicio), MONTH(NOW())) AS mes,
    COALESCE(YEAR(a.data_inicio), YEAR(NOW())) AS ano,
    COUNT(DISTINCT a.idassinatura) AS total_assinaturas,
    COUNT(DISTINCT CASE WHEN a.status = 'ativa' THEN a.idassinatura END) AS ativas,
    COUNT(DISTINCT CASE WHEN a.status = 'suspensa' THEN a.idassinatura END) AS suspensas,
    COUNT(DISTINCT CASE WHEN a.status = 'cancelada' THEN a.idassinatura END) AS canceladas,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE 0 END), 2) AS receita_base,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN COALESCE(aa_total.custo_addons, 0) ELSE 0 END), 2) AS receita_addons,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE 0 END) +
          SUM(CASE WHEN a.status = 'ativa' THEN COALESCE(aa_total.custo_addons, 0) ELSE 0 END), 2) AS receita_total
FROM assinaturas a
LEFT JOIN (
    SELECT 
        idassinatura,
        ROUND(SUM(COALESCE(preco_unitario, 0) * quantidade), 2) AS custo_addons
    FROM assinaturas_addons
    GROUP BY idassinatura
) aa_total ON a.idassinatura = aa_total.idassinatura
WHERE a.data_inicio IS NOT NULL
GROUP BY MONTH(a.data_inicio), YEAR(a.data_inicio)
ORDER BY ano DESC, mes DESC;