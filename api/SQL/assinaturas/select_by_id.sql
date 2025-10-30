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
    a.preco_sem_imposto,
    a.aliquota_imposto_percent,
    a.preco_com_imposto,
    a.observacoes,
    a.criado_em,
    a.atualizado_em,
    c.nome as cliente_nome,
    s.nome as sistema_nome,
    sp.nome as plano_nome
FROM assinaturas a
INNER JOIN clientes c ON c.idcliente = a.idcliente
INNER JOIN sistemas s ON s.idsistema = a.idsistema
LEFT JOIN sistemas_planos sp ON sp.idplano = a.idplano
WHERE a.idassinatura = :idassinatura;