INSERT INTO precos_historico 
(tipo_referencia, id_referencia, campo_alterado, valor_anterior, valor_novo, 
 aliquota_anterior, aliquota_nova, iduser_alteracao, motivo, data_alteracao)
VALUES 
(:tipo_referencia, :id_referencia, :campo_alterado, :valor_anterior, :valor_novo,
 :aliquota_anterior, :aliquota_nova, :iduser_alteracao, :motivo, NOW());