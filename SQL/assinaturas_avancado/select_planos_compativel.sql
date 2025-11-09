-- Busca planos compatíveis para mudança (mesmo sistema, diferentes preços)
-- Parâmetros: :idsistema
SELECT 
    sp.idplano,
    sp.idsistema,
    sp.descricao,
    sp.preco,
    sp.limite_usuarios,
    sp.limite_dispositivos,
    sp.ativo,
    s.nome as sistema_nome,
    COUNT(DISTINCT a.idassinatura) as total_assinantes
FROM sistemas_planos sp
INNER JOIN sistemas s ON sp.idsistema = s.idsistema
LEFT JOIN assinaturas a ON sp.idplano = a.idplano AND a.status = 'ativa'
WHERE sp.idsistema = :idsistema
  AND sp.ativo = 1
GROUP BY sp.idplano, sp.idsistema, sp.descricao, sp.preco, sp.limite_usuarios, sp.limite_dispositivos, sp.ativo, s.nome
ORDER BY sp.preco ASC;
