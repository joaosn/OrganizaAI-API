INSERT INTO sistemas_addons (
    idsistema,
    nome,
    descricao,
    preco_sem_imposto,
    aliquota_imposto_percent,
    ativo
) VALUES (
    :idsistema,
    :nome,
    :descricao,
    :preco_sem_imposto,
    :aliquota_imposto_percent,
    COALESCE(:ativo, 1)
);