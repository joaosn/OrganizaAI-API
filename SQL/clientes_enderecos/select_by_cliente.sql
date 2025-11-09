SELECT 
    ce.idendereco,
    ce.idcliente,
    ce.tipo,
    ce.logradouro,
    ce.numero,
    ce.complemento,
    ce.bairro,
    ce.cidade,
    ce.uf,
    ce.cep,
    ce.pais,
    ce.principal
FROM clientes_enderecos ce
WHERE ce.idcliente = :idcliente
ORDER BY ce.principal DESC, ce.tipo;