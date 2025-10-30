SELECT 
    a.idassinatura,
    a.idcliente,
    a.idsistema,
    a.idplano,
    a.ciclo_cobranca,
    a.dia_vencimento,
    a.data_inicio,
    a.data_fim,
    a.status,
    a.preco_com_imposto,
    a.observacoes,
    a.criado_em,
    c.nome as cliente_nome,
    s.nome as sistema_nome
FROM assinaturas a
INNER JOIN clientes c ON c.idcliente = a.idcliente
INNER JOIN sistemas s ON s.idsistema = a.idsistema
WHERE a.status = 'ativa'
ORDER BY a.criado_em DESC;