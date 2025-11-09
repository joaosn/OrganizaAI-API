SELECT 
    s.idsistema,
    s.nome AS sistema_nome,
    COUNT(DISTINCT a.idassinatura) AS total_assinaturas,
    COUNT(DISTINCT CASE WHEN a.status = 'ativa' THEN a.idassinatura END) AS assinaturas_ativas,
    COUNT(DISTINCT CASE WHEN a.status = 'suspensa' THEN a.idassinatura END) AS assinaturas_suspensas,
    COUNT(DISTINCT CASE WHEN a.status = 'cancelada' THEN a.idassinatura END) AS assinaturas_canceladas,
    ROUND(SUM(CASE WHEN a.status = 'ativa' THEN a.preco_sem_imposto * (1 + a.aliquota_imposto_percent/100) ELSE 0 END), 2) AS receita_mensal
FROM sistemas s
LEFT JOIN assinaturas a ON s.idsistema = a.idsistema
WHERE s.ativo = 1
GROUP BY s.idsistema, s.nome
ORDER BY receita_mensal DESC;