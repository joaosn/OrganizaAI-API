SELECT 
    sp.idplano,
    sp.idsistema,
    sp.nome,
    sp.descricao,
    sp.ciclo_cobranca,
    sp.preco_base_sem_imposto,
    sp.aliquota_imposto_percent,
    sp.preco_base_com_imposto,
    sp.ativo,
    s.nome as sistema_nome
FROM sistemas_planos sp
INNER JOIN sistemas s ON s.idsistema = sp.idsistema
WHERE sp.idplano = :idplano;