SELECT 
    s.idsistema,
    s.nome,
    s.categoria,
    s.descricao,
    s.ativo
FROM sistemas s
WHERE s.ativo = 1
ORDER BY s.nome;