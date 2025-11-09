INSERT INTO assinaturas_addons (
    idassinatura,
    idaddon,
    quantidade,
    preco_sem_imposto,
    aliquota_imposto_percent,
    ativo
) VALUES (
    :idassinatura,
    :idaddon,
    :quantidade,
    :preco_sem_imposto,
    :aliquota_imposto_percent,
    COALESCE(:ativo, 1)
);