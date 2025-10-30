INSERT INTO sistemas_planos (
    idsistema,
    nome,
    descricao,
    ciclo_cobranca,
    preco_base_sem_imposto,
    aliquota_imposto_percent,
    ativo
) VALUES (
    :idsistema,
    :nome,
    :descricao,
    :ciclo_cobranca,
    :preco_base_sem_imposto,
    :aliquota_imposto_percent,
    COALESCE(:ativo, 1)
);