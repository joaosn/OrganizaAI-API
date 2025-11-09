INSERT INTO sistemas (
    nome,
    categoria,
    descricao,
    ativo
) VALUES (
    :nome,
    :categoria,
    :descricao,
    COALESCE(:ativo, 1)
);