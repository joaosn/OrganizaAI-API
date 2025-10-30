-- Registra mudança de plano com histórico de preços
-- Parâmetros: :idassinatura, :idplano_novo, :preco_anterior, :preco_novo, :tipo_mudanca
INSERT INTO precos_historico (
    tipo_referencia,
    referencia_id,
    campo_alterado,
    valor_anterior,
    valor_novo,
    aliquota_anterior,
    aliquota_novo,
    usuario_id,
    motivo_alteracao,
    data_alteracao
)
SELECT 
    'assinatura' as tipo_referencia,
    :idassinatura as referencia_id,
    'preco_negociado' as campo_alterado,
    :preco_anterior as valor_anterior,
    :preco_novo as valor_novo,
    a.aliquota_imposto as aliquota_anterior,
    sp.preco as aliquota_novo,
    :usuario_id as usuario_id,
    CONCAT(:tipo_mudanca, ' - Plano alterado') as motivo_alteracao,
    NOW() as data_alteracao
FROM assinaturas a
INNER JOIN sistemas_planos sp ON sp.idplano = :idplano_novo
WHERE a.idassinatura = :idassinatura;
