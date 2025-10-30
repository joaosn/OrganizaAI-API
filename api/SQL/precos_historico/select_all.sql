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
WHERE (:tipo_referencia IS NULL OR tipo_referencia = :tipo_referencia)
  AND (:id_referencia IS NULL OR id_referencia = :id_referencia)
  AND (:data_inicio IS NULL OR data_alteracao >= :data_inicio)
  AND (:data_fim IS NULL OR data_alteracao <= :data_fim)
ORDER BY data_alteracao DESC
LIMIT :limit OFFSET :offset;