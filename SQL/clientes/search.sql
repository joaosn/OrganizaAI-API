SELECT 
    c.idcliente,
    c.tipo_pessoa,
    c.nome,
    c.nome_fantasia,
    c.cpf_cnpj,
    c.email,
    c.telefone,
    c.ativo,
    c.data_cadastro
FROM clientes c
WHERE (
    c.nome LIKE CONCAT('%', :termo, '%')
    OR c.nome_fantasia LIKE CONCAT('%', :termo, '%')
    OR c.cpf_cnpj LIKE CONCAT('%', :termo, '%')
    OR c.email LIKE CONCAT('%', :termo, '%')
)
  AND (:ativo IS NULL OR c.ativo = :ativo)
  AND (:tipo_pessoa IS NULL OR c.tipo_pessoa = :tipo_pessoa)
ORDER BY c.nome
LIMIT :limit OFFSET :offset;