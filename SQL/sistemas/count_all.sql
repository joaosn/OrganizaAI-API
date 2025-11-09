SELECT COUNT(*) as total
FROM sistemas s
WHERE (:ativo IS NULL OR s.ativo = :ativo)
  AND (:categoria IS NULL OR s.categoria = :categoria);