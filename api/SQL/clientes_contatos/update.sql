UPDATE clientes_contatos SET
    nome = :nome,
    email = :email,
    telefone = :telefone,
    cargo = :cargo,
    principal = :principal
WHERE idcontato = :idcontato;