UPDATE sistemas_addons SET
    idsistema = :idsistema,
    nome = :nome,
    descricao = :descricao,
    preco_sem_imposto = :preco_sem_imposto,
    aliquota_imposto_percent = :aliquota_imposto_percent,
    ativo = :ativo
WHERE idaddon = :idaddon;