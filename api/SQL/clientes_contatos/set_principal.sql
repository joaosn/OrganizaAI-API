-- Define contato como principal
UPDATE clientes_contatos 
SET principal = 1 
WHERE idcontato = :idcontato;