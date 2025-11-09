-- Calcula valor de reembolso no cancelamento (pro-rata retroativo)
-- Parâmetros: :idassinatura, :data_cancelamento
SELECT 
    a.idassinatura,
    a.preco_negociado,
    a.data_vencimento,
    :data_cancelamento as data_cancelamento,
    DATEDIFF(:data_cancelamento, a.data_inicio) as dias_usados,
    DAY(LAST_DAY(a.data_vencimento)) as dias_no_mes,
    ROUND(
        (DATEDIFF(:data_cancelamento, a.data_inicio) / DAY(LAST_DAY(a.data_vencimento))) * 100,
        2
    ) as percentual_uso,
    ROUND(
        a.preco_negociado * (1 + a.aliquota_imposto / 100),
        2
    ) as valor_com_impostos,
    ROUND(
        (a.preco_negociado * (DATEDIFF(:data_cancelamento, a.data_inicio) / DAY(LAST_DAY(a.data_vencimento)))) * (1 + a.aliquota_imposto / 100),
        2
    ) as valor_cobrar_proporcional,
    ROUND(
        (a.preco_negociado * (1 + a.aliquota_imposto / 100)) - 
        (a.preco_negociado * (DATEDIFF(:data_cancelamento, a.data_inicio) / DAY(LAST_DAY(a.data_vencimento)))) * (1 + a.aliquota_imposto / 100),
        2
    ) as valor_reembolso
FROM assinaturas a
WHERE a.idassinatura = :idassinatura;
