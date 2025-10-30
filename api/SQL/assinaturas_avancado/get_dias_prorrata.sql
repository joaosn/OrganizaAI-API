-- Calcula dias de pro-rata entre data_inicio e data_fim
-- Parâmetros: :data_inicio, :data_fim
SELECT 
    DATEDIFF(:data_fim, :data_inicio) as dias_decorridos,
    DAY(LAST_DAY(:data_fim)) as dias_mes_atual,
    ROUND(
        (DATEDIFF(:data_fim, :data_inicio) / DAY(LAST_DAY(:data_fim))) * 100,
        2
    ) as percentual_mes;
