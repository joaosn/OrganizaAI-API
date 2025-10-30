INSERT INTO clientes_contatos (
    idcliente,
    nome,
    email,
    telefone,
    cargo,
    principal
) VALUES (
    :idcliente,
    :nome,
    :email,
    :telefone,
    :cargo,
    COALESCE(:principal, 0)
);