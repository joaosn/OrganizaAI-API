SELECT 
    aa.idassinatura_addon,
    aa.idassinatura,
    aa.idaddon,
    aa.quantidade,
    aa.preco_sem_imposto,
    aa.aliquota_imposto_percent,
    aa.preco_com_imposto,
    aa.ativo,
    sa.nome as addon_nome,
    sa.descricao as addon_descricao
FROM assinaturas_addons aa
INNER JOIN sistemas_addons sa ON sa.idaddon = aa.idaddon
WHERE aa.idassinatura = :idassinatura
  AND (:ativo IS NULL OR aa.ativo = :ativo)
ORDER BY sa.nome;