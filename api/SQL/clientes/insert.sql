INSERT INTO clientes (
    tipo_pessoa,
    nome,
    nome_fantasia,
    cpf_cnpj,
    ie_rg,
    im,
    email,
    telefone,
    ativo
) VALUES (
    :tipo_pessoa,
    :nome,
    :nome_fantasia,
    :cpf_cnpj,
    :ie_rg,
    :im,
    :email,
    :telefone,
    COALESCE(:ativo, 1)
);