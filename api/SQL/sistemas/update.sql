UPDATE sistemas SET
    nome = :nome,
    categoria = :categoria,
    descricao = :descricao,
    ativo = :ativo
WHERE idsistema = :idsistema;