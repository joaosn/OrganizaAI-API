-- Calcula total mensal de uma assinatura incluindo add-ons
SELECT 
    COALESCE(
        (SELECT preco_com_imposto FROM v_assinaturas_resumo WHERE idassinatura = :idassinatura),
        0
    )
    +
    COALESCE((
        SELECT COALESCE(SUM(preco_com_imposto * quantidade), 0)
        FROM assinaturas_addons
        WHERE idassinatura = :idassinatura
          AND ativo = 1
    ), 0) as total_com_imposto;