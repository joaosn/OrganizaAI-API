-- Assinaturas vencendo nos próximos X dias
SELECT 
    a.idassinatura,
    a.idcliente,
    a.idsistema,
    a.ciclo_cobranca,
    a.dia_vencimento,
    ADDDATE(
        DATE_SUB(NOW(), INTERVAL DAY(NOW()) - :dia_vencimento DAY),
        IF(DAY(NOW()) > :dia_vencimento, INTERVAL 1 MONTH, INTERVAL 0 MONTH)
    ) as proxima_data_vencimento,
    a.preco_com_imposto,
    a.status,
    c.nome as cliente_nome,
    s.nome as sistema_nome
FROM assinaturas a
INNER JOIN clientes c ON c.idcliente = a.idcliente
INNER JOIN sistemas s ON s.idsistema = a.idsistema
WHERE a.status = 'ativa'
  AND DATEDIFF(
      ADDDATE(
          DATE_SUB(NOW(), INTERVAL DAY(NOW()) - :dia_vencimento DAY),
          IF(DAY(NOW()) > :dia_vencimento, INTERVAL 1 MONTH, INTERVAL 0 MONTH)
      ),
      CURDATE()
  ) <= :dias
  AND DATEDIFF(
      ADDDATE(
          DATE_SUB(NOW(), INTERVAL DAY(NOW()) - :dia_vencimento DAY),
          IF(DAY(NOW()) > :dia_vencimento, INTERVAL 1 MONTH, INTERVAL 0 MONTH)
      ),
      CURDATE()
  ) > 0
ORDER BY proxima_data_vencimento ASC;