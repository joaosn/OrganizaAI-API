UPDATE sistemas_planos SET
    idsistema = :idsistema,
    nome = :nome,
    descricao = :descricao,
    ciclo_cobranca = :ciclo_cobranca,
    preco_base_sem_imposto = :preco_base_sem_imposto,
    aliquota_imposto_percent = :aliquota_imposto_percent,
    ativo = :ativo
WHERE idplano = :idplano;