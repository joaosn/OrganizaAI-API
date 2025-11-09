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
WHERE sa.idsistema = :idsistema
  AND (:ativo IS NULL OR sa.ativo = :ativo)
ORDER BY sa.nome;