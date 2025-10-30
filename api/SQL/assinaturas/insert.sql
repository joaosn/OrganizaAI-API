INSERT INTO assinaturas (
    idcliente,
    idsistema,
    idplano,
    ciclo_cobranca,
    dia_vencimento,
    data_inicio,
    data_fim,
    status,
    preco_sem_imposto,
    aliquota_imposto_percent,
    observacoes
) VALUES (
    :idcliente,
    :idsistema,
    :idplano,
    :ciclo_cobranca,
    :dia_vencimento,
    :data_inicio,
    :data_fim,
    COALESCE(:status, 'ativa'),
    :preco_sem_imposto,
    :aliquota_imposto_percent,
    :observacoes
);