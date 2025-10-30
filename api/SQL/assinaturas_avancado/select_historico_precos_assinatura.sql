-- Busca histórico de alterações de preço de uma assinatura para cálculo de pro-rata
-- Parâmetros: :idassinatura, :data_inicio, :data_fim
SELECT 
    h.idhistorico,
    h.data_alteracao,
    h.tipo_referencia,
    h.referencia_id,
    h.campo_alterado,
    h.valor_anterior,
    h.valor_novo,
    h.aliquota_anterior,
    h.aliquota_novo,
    h.usuario_id,
    h.motivo_alteracao,
    CASE 
        WHEN h.tipo_referencia = 'assinatura' THEN 'Assinatura'
        WHEN h.tipo_referencia = 'assinatura_addon' THEN 'Add-on'
        ELSE 'Outro'
    END as tipo_alteracao
FROM precos_historico h
WHERE (
    (h.tipo_referencia = 'assinatura' AND h.referencia_id = :idassinatura) OR
    (h.tipo_referencia = 'assinatura_addon' AND h.referencia_id IN (
        SELECT idassinatura_addon FROM assinaturas_addons WHERE idassinatura = :idassinatura
    ))
)
  AND h.data_alteracao BETWEEN :data_inicio AND :data_fim
ORDER BY h.data_alteracao DESC;
