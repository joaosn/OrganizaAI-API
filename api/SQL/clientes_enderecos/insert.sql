INSERT INTO clientes_enderecos (
    idcliente,
    tipo,
    logradouro,
    numero,
    complemento,
    bairro,
    cidade,
    uf,
    cep,
    pais,
    principal
) VALUES (
    :idcliente,
    :tipo,
    :logradouro,
    :numero,
    :complemento,
    :bairro,
    :cidade,
    :uf,
    :cep,
    COALESCE(:pais, 'BR'),
    COALESCE(:principal, 0)
);