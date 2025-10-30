SELECT COUNT(*) as total
FROM clientes c
WHERE (:ativo IS NULL OR c.ativo = :ativo)
  AND (:tipo_pessoa IS NULL OR c.tipo_pessoa = :tipo_pessoa);