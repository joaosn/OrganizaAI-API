-- Atualiza dados de assinatura (preço, alíquota, dados de vencimento)
-- Parâmetros: :idassinatura, :preco_novo, :aliquota_nova, :data_vencimento_novo, :motivo_alteracao
UPDATE assinaturas
SET 
    preco_negociado = :preco_novo,
    aliquota_imposto = :aliquota_nova,
    data_vencimento = :data_vencimento_novo,
    data_atualizacao = NOW()
WHERE idassinatura = :idassinatura;
