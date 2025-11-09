-- Registra cancelamento de assinatura
-- Parâmetros: :idassinatura, :motivo_cancelamento, :data_cancelamento
UPDATE assinaturas
SET 
    status = 'cancelada',
    data_vencimento = :data_cancelamento,
    motivo_cancelamento = :motivo_cancelamento,
    data_atualizacao = NOW()
WHERE idassinatura = :idassinatura;

-- Cancelar também os add-ons ativos
UPDATE assinaturas_addons
SET 
    ativo = 0,
    data_desativacao = NOW()
WHERE idassinatura = :idassinatura
  AND ativo = 1;
