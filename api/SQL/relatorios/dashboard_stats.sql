SELECT 
    COUNT(DISTINCT c.idcliente) AS total_clientes,
    COUNT(DISTINCT CASE WHEN c.ativo = 1 THEN c.idcliente END) AS clientes_ativos,
    COUNT(DISTINCT s.idsistema) AS total_sistemas,
    COUNT(DISTINCT CASE WHEN s.ativo = 1 THEN s.idsistema END) AS sistemas_ativos,
    COUNT(DISTINCT a.idassinatura) AS total_assinaturas,
    COUNT(DISTINCT CASE WHEN a.status = 'ativa' THEN a.idassinatura END) AS assinaturas_ativas,
    COUNT(DISTINCT CASE WHEN a.status = 'suspensa' THEN a.idassinatura END) AS assinaturas_suspensas,
    COUNT(DISTINCT CASE WHEN a.status = 'cancelada' THEN a.idassinatura END) AS assinaturas_canceladas,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE 0 END), 2) AS receita_mensal_total,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN COALESCE(aa_total.custo_addons, 0) ELSE 0 END), 2) AS receita_addons_total,
    ROUND(AVG(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE NULL END), 2) AS ticket_medio
FROM clientes c
CROSS JOIN sistemas s
LEFT JOIN assinaturas a ON c.idcliente = a.idcliente AND s.idsistema = a.idsistema
LEFT JOIN (
    SELECT 
        idassinatura,
        ROUND(SUM(COALESCE(preco_unitario, 0) * quantidade), 2) AS custo_addons
    FROM assinaturas_addons
    GROUP BY idassinatura
) aa_total ON a.idassinatura = aa_total.idassinatura;