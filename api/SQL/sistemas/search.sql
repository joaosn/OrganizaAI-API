SELECT 
    s.idsistema,
    s.nome,
    s.categoria,
    s.descricao,
    s.ativo
FROM sistemas s
WHERE (
    s.nome LIKE CONCAT('%', :termo, '%')
    OR s.categoria LIKE CONCAT('%', :termo, '%')
    OR s.descricao LIKE CONCAT('%', :termo, '%')
)
  AND (:ativo IS NULL OR s.ativo = :ativo)
  AND (:categoria IS NULL OR s.categoria = :categoria)
ORDER BY s.nome
LIMIT :limit OFFSET :offset;