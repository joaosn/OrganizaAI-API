DELETE FROM precos_historico
WHERE data_alteracao < DATE_SUB(NOW(), INTERVAL :dias_retencao DAY);