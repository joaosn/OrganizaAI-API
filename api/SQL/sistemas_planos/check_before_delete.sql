-- Verifica se plano possui assinaturas ativas antes da exclusão
SELECT COUNT(*) as assinaturas_ativas
FROM assinaturas 
WHERE idplano = :idplano 
  AND status IN ('ativa', 'trial');