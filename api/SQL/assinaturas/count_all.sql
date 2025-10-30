SELECT COUNT(*) as total
FROM assinaturas a
WHERE (:status IS NULL OR a.status = :status)
  AND (:idcliente IS NULL OR a.idcliente = :idcliente)
  AND (:idsistema IS NULL OR a.idsistema = :idsistema);