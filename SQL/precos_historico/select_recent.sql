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
WHERE data_alteracao >= DATE_SUB(NOW(), INTERVAL :dias DAY)
ORDER BY data_alteracao DESC
LIMIT :limit;