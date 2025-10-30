-- Verifica se sistema possui assinaturas ativas antes da exclusão
SELECT COUNT(*) as assinaturas_ativas
FROM assinaturas 
WHERE idsistema = :idsistema 
  AND status IN ('ativa', 'trial');