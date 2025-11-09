-- Verifica se cliente possui assinaturas ativas antes da exclusão
SELECT COUNT(*) as assinaturas_ativas
FROM assinaturas 
WHERE idcliente = :idcliente 
  AND status IN ('ativa', 'trial');