-- Atualiza apenas o status da assinatura
UPDATE assinaturas SET
    status = :status
WHERE idassinatura = :idassinatura;