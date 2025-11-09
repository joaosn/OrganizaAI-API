SELECT 
    c.idcliente,
    c.tipo_pessoa,
    c.nome,
    c.nome_fantasia,
    c.cpf_cnpj,
    c.ie_rg,
    c.im,
    c.email,
    c.telefone,
    c.data_cadastro,
    c.ativo
FROM clientes c
WHERE c.idcliente = :idcliente;