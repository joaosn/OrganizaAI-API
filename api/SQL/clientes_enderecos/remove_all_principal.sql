-- Remove principal de todos os endereços do cliente
UPDATE clientes_enderecos 
SET principal = 0 
WHERE idcliente = :idcliente;