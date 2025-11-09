SELECT 
    cc.idcontato,
    cc.idcliente,
    cc.nome,
    cc.email,
    cc.telefone,
    cc.cargo,
    cc.principal
FROM clientes_contatos cc
WHERE cc.idcontato = :idcontato;