UPDATE assinaturas SET
    idcliente = :idcliente,
    idsistema = :idsistema,
    idplano = :idplano,
    ciclo_cobranca = :ciclo_cobranca,
    dia_vencimento = :dia_vencimento,
    data_inicio = :data_inicio,
    data_fim = :data_fim,
    status = :status,
    preco_sem_imposto = :preco_sem_imposto,
    aliquota_imposto_percent = :aliquota_imposto_percent,
    observacoes = :observacoes
WHERE idassinatura = :idassinatura;