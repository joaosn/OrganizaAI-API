SELECT 
    sa.idaddon,
    sa.idsistema,
    sa.nome,
    sa.descricao,
    sa.preco_sem_imposto,
    sa.aliquota_imposto_percent,
    sa.preco_com_imposto,
    sa.ativo,
    s.nome as sistema_nome
FROM sistemas_addons sa
INNER JOIN sistemas s ON s.idsistema = sa.idsistema
WHERE sa.ativo = 1
  AND s.ativo = 1
ORDER BY s.nome, sa.nome;