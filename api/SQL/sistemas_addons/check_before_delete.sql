-- Verifica se add-on possui assinaturas ativas antes da exclusão
SELECT COUNT(*) as assinaturas_ativas
FROM assinaturas_addons aa
INNER JOIN assinaturas a ON a.idassinatura = aa.idassinatura
WHERE aa.idaddon = :idaddon 
  AND aa.ativo = 1
  AND a.status IN ('ativa', 'trial');