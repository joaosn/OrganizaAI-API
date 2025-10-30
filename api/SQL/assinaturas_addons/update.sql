UPDATE assinaturas_addons SET
    idassinatura = :idassinatura,
    idaddon = :idaddon,
    quantidade = :quantidade,
    preco_sem_imposto = :preco_sem_imposto,
    aliquota_imposto_percent = :aliquota_imposto_percent,
    ativo = :ativo
WHERE idassinatura_addon = :idassinatura_addon;