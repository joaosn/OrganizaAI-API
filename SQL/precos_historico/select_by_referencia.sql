SELECT 
    idpreco_historico,
    tipo_referencia,
    id_referencia,
    campo_alterado,
    valor_anterior,
    valor_novo,
    aliquota_anterior,
    aliquota_nova,
    iduser_alteracao,
    motivo,
    data_alteracao
FROM precos_historico
WHERE tipo_referencia = :tipo_referencia
  AND id_referencia = :id_referencia
ORDER BY data_alteracao DESC;