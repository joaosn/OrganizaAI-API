-- Remove principal de todos os contatos do cliente
UPDATE clientes_contatos 
SET principal = 0 
WHERE idcliente = :idcliente;