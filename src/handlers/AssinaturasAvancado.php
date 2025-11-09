<?php

namespace src\handlers;

use src\models\AssinaturasModel;
use src\models\AssinaturasAddonsModel;
use src\models\PrecosHistoricoModel;

class AssinaturasAvancado
{
    private $assinaturasModel;
    private $assinaturasAddonsModel;
    private $precosHistoricoModel;

    public function __construct()
    {
        $this->assinaturasModel = new AssinaturasModel();
        $this->assinaturasAddonsModel = new AssinaturasAddonsModel();
        $this->precosHistoricoModel = new PrecosHistoricoModel();
    }

    /**
     * Obtém assinaturas elegíveis para renovação
     * 
     * @param int $diasAntecedencia Dias antes do vencimento
     * @return array
     */
    public function obterAssinaturasParaRenovar($diasAntecedencia = 5)
    {
        // Validar dias antecedência
        if ($diasAntecedencia <= 0) {
            throw new \Exception("Dias de antecedência deve ser maior que zero");
        }

        $resultado = \core\Database::switchParams(
            ['dias_antecedencia' => $diasAntecedencia],
            'assinaturas_avancado/select_para_renovar',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new \Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Calcula pro-rata (valor proporcional ao período usado)
     * 
     * @param int $idassinatura ID da assinatura
     * @param string $dataInicio Data de início (Y-m-d)
     * @param string $dataFim Data de fim (Y-m-d)
     * @return array
     */
    public function calcularProrrata($idassinatura, $dataInicio, $dataFim)
    {
        // Validar datas
        if (strtotime($dataInicio) > strtotime($dataFim)) {
            throw new \Exception("Data início não pode ser maior que data fim");
        }

        // Buscar dados da assinatura
        $assinatura = $this->assinaturasModel->buscarPorId($idassinatura);
        if (!$assinatura) {
            throw new \Exception("Assinatura não encontrada");
        }

        // Calcular dias de pro-rata
        $diasResult = \core\Database::switchParams(
            [
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim
            ],
            'assinaturas_avancado/get_dias_prorrata',
            true,
            false,
            false
        );

        if ($diasResult['error']) {
            throw new \Exception($diasResult['error']);
        }

        $dias = $diasResult['retorno'][0];
        $percentualMes = $dias['percentual_mes'] / 100;

        // Calcular valor com impostos
        $valorComImpostos = $assinatura['preco_negociado'] * (1 + $assinatura['aliquota_imposto'] / 100);
        $valorProrrata = $valorComImpostos * $percentualMes;

        // Buscar add-ons
        $addons = $this->assinaturasAddonsModel->listarPorAssinatura($idassinatura);
        $totalAddonsComImpostos = 0;
        if ($addons) {
            foreach ($addons as $addon) {
                $totalAddonsComImpostos += $addon['preco'] * (1 + ($addon['aliquota_imposto'] ?? 0) / 100);
            }
        }
        $totalAddonsProrrata = $totalAddonsComImpostos * $percentualMes;

        return [
            'idassinatura' => $idassinatura,
            'dias_decorridos' => $dias['dias_decorridos'],
            'dias_mes_atual' => $dias['dias_mes_atual'],
            'percentual_mes' => $dias['percentual_mes'],
            'valor_plano' => $assinatura['preco_negociado'],
            'valor_plano_com_impostos' => $valorComImpostos,
            'valor_plano_prorrata' => round($valorProrrata, 2),
            'total_addons' => count($addons),
            'valor_addons_com_impostos' => round($totalAddonsComImpostos, 2),
            'valor_addons_prorrata' => round($totalAddonsProrrata, 2),
            'valor_total_prorrata' => round($valorProrrata + $totalAddonsProrrata, 2)
        ];
    }

    /**
     * Renova assinatura (estende vencimento, atualiza preços)
     * 
     * @param int $idassinatura ID da assinatura
     * @param float $novoPreco Novo preço negociado (opcional)
     * @param float $novaAliquota Nova alíquota de imposto (opcional)
     * @param int $usuarioId ID do usuário que realiza renovação
     * @return array
     */
    public function renovarAssinatura($idassinatura, $novoPreco = null, $novaAliquota = null, $usuarioId = null)
    {
        // Buscar assinatura
        $assinatura = $this->assinaturasModel->buscarPorId($idassinatura);
        if (!$assinatura) {
            throw new \Exception("Assinatura não encontrada");
        }

        if ($assinatura['status'] !== 'ativa' && $assinatura['status'] !== 'suspensa') {
            throw new \Exception("Assinatura deve estar ativa ou suspensa para renovar");
        }

        // Usar preço atual se não informado novo preço
        if ($novoPreco === null) {
            $novoPreco = $assinatura['preco_negociado'];
        }

        if ($novaAliquota === null) {
            $novaAliquota = $assinatura['aliquota_imposto'];
        }

        // Validar valores
        if ($novoPreco < 0) {
            throw new \Exception("Preço não pode ser negativo");
        }

        if ($novaAliquota < 0 || $novaAliquota > 100) {
            throw new \Exception("Alíquota deve estar entre 0 e 100");
        }

        // Calcular nova data de vencimento (30 dias a partir de hoje)
        $dataVencimento = date('Y-m-d', strtotime('+30 days'));

        // Atualizar assinatura
        $resultado = \core\Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'preco_novo' => $novoPreco,
                'aliquota_nova' => $novaAliquota,
                'data_vencimento_novo' => $dataVencimento,
                'motivo_alteracao' => 'Renovação'
            ],
            'assinaturas_avancado/update_assinatura_dados',
            true,
            false,
            true
        );

        if ($resultado['error']) {
            throw new \Exception($resultado['error']);
        }

        // Registrar alteração de preço no histórico
        if ($novoPreco !== $assinatura['preco_negociado']) {
            $this->precosHistoricoModel->registrarAlteracao(
                'assinatura',
                $idassinatura,
                'preco_negociado',
                $assinatura['preco_negociado'],
                $novoPreco,
                $assinatura['aliquota_imposto'],
                $novaAliquota,
                $usuarioId ?? 0,
                'Renovação - Alteração de preço'
            );
        }

        return [
            'success' => true,
            'idassinatura' => $idassinatura,
            'novo_preco' => $novoPreco,
            'nova_aliquota' => $novaAliquota,
            'novo_vencimento' => $dataVencimento,
            'message' => 'Assinatura renovada com sucesso'
        ];
    }

    /**
     * Muda plano de assinatura (upgrade/downgrade)
     * 
     * @param int $idassinatura ID da assinatura
     * @param int $idplanonovo ID do novo plano
     * @param string $tipoMudanca 'upgrade' ou 'downgrade'
     * @param int $usuarioId ID do usuário que realiza mudança
     * @return array
     */
    public function mudarPlano($idassinatura, $idplanonovo, $tipoMudanca = 'upgrade', $usuarioId = null)
    {
        // Validar tipo de mudança
        if (!in_array($tipoMudanca, ['upgrade', 'downgrade'])) {
            throw new \Exception("Tipo de mudança deve ser 'upgrade' ou 'downgrade'");
        }

        // Buscar assinatura atual
        $assinatura = $this->assinaturasModel->buscarPorId($idassinatura);
        if (!$assinatura) {
            throw new \Exception("Assinatura não encontrada");
        }

        if ($assinatura['status'] !== 'ativa') {
            throw new \Exception("Apenas assinaturas ativas podem mudar de plano");
        }

        // Buscar novo plano
        $novoPlano = \core\Database::switchParams(
            ['idplano' => $idplanonovo],
            'sistemas_planos/select_by_id',
            true,
            false,
            false
        );

        if ($novoPlano['error'] || empty($novoPlano['retorno'])) {
            throw new \Exception("Novo plano não encontrado");
        }

        $novoPlano = $novoPlano['retorno'][0];

        // Validar que é do mesmo sistema
        if ($novoPlano['idsistema'] !== $assinatura['idsistema']) {
            throw new \Exception("Mudança apenas permitida para planos do mesmo sistema");
        }

        // Validar que é um plano diferente
        if ($novoPlano['idplano'] === $assinatura['idplano']) {
            throw new \Exception("Novo plano deve ser diferente do plano atual");
        }

        // Calcular pro-rata
        $prorrata = $this->calcularProrrata(
            $idassinatura,
            date('Y-m-d'),
            $assinatura['data_vencimento']
        );

        $precoAtual = $assinatura['preco_negociado'];
        $precoNovo = $novoPlano['preco'];
        
        // Calcular ajuste financeiro
        $diferenca = $precoNovo - $precoAtual;
        $diferencaProrrata = $diferenca * ($prorrata['percentual_mes'] / 100);

        // Atualizar assinatura com novo plano
        $resultado = \core\Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'preco_novo' => $precoNovo,
                'aliquota_nova' => $assinatura['aliquota_imposto'],
                'data_vencimento_novo' => $assinatura['data_vencimento'],
                'motivo_alteracao' => 'Mudança de plano'
            ],
            'assinaturas_avancado/update_assinatura_dados',
            true,
            false,
            true
        );

        if ($resultado['error']) {
            throw new \Exception($resultado['error']);
        }

        // Registrar mudança no histórico
        $this->precosHistoricoModel->registrarAlteracao(
            'assinatura',
            $idassinatura,
            'idplano_e_preco',
            $assinatura['idplano'] . ':' . $precoAtual,
            $idplanonovo . ':' . $precoNovo,
            $assinatura['aliquota_imposto'],
            $assinatura['aliquota_imposto'],
            $usuarioId ?? 0,
            ucfirst($tipoMudanca) . ' - Mudança de plano'
        );

        return [
            'success' => true,
            'idassinatura' => $idassinatura,
            'plano_anterior' => $assinatura['idplano'],
            'plano_novo' => $idplanonovo,
            'preco_anterior' => $precoAtual,
            'preco_novo' => $precoNovo,
            'diferenca_mensal' => round($diferenca, 2),
            'diferenca_prorrata' => round($diferencaProrrata, 2),
            'ajuste_financeiro' => $diferenca > 0 ? 'COBRAR' : 'REEMBOLSAR',
            'message' => ucfirst($tipoMudanca) . ' realizado com sucesso'
        ];
    }

    /**
     * Cancela assinatura e calcula reembolso (pro-rata)
     * 
     * @param int $idassinatura ID da assinatura
     * @param string $motivo Motivo do cancelamento
     * @return array
     */
    public function cancelarAssinatura($idassinatura, $motivo = 'Cancelamento por solicitação')
    {
        // Buscar assinatura
        $assinatura = $this->assinaturasModel->buscarPorId($idassinatura);
        if (!$assinatura) {
            throw new \Exception("Assinatura não encontrada");
        }

        if ($assinatura['status'] === 'cancelada') {
            throw new \Exception("Assinatura já foi cancelada");
        }

        // Validar motivo
        if (empty($motivo) || strlen($motivo) < 3) {
            throw new \Exception("Motivo do cancelamento deve ter no mínimo 3 caracteres");
        }

        // Calcular valor de reembolso
        $dataCancelamento = date('Y-m-d');
        $valorResult = \core\Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'data_cancelamento' => $dataCancelamento
            ],
            'assinaturas_avancado/select_valor_cancelamento',
            true,
            false,
            false
        );

        if ($valorResult['error'] || empty($valorResult['retorno'])) {
            throw new \Exception("Erro ao calcular reembolso");
        }

        $dadosReembolso = $valorResult['retorno'][0];

        // Registrar cancelamento
        $cancelResult = \core\Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'motivo_cancelamento' => $motivo,
                'data_cancelamento' => $dataCancelamento
            ],
            'assinaturas_avancado/update_cancelamento',
            true,
            false,
            true
        );

        if ($cancelResult['error']) {
            throw new \Exception($cancelResult['error']);
        }

        // Registrar no histórico
        $this->precosHistoricoModel->registrarAlteracao(
            'assinatura',
            $idassinatura,
            'status',
            $assinatura['status'],
            'cancelada',
            0,
            0,
            0,
            'Cancelamento - ' . $motivo
        );

        return [
            'success' => true,
            'idassinatura' => $idassinatura,
            'status_anterior' => $assinatura['status'],
            'status_novo' => 'cancelada',
            'data_cancelamento' => $dataCancelamento,
            'dias_usados' => $dadosReembolso['dias_usados'],
            'percentual_uso' => $dadosReembolso['percentual_uso'] . '%',
            'valor_com_impostos' => $dadosReembolso['valor_com_impostos'],
            'valor_cobrar_proporcional' => $dadosReembolso['valor_cobrar_proporcional'],
            'valor_reembolso' => $dadosReembolso['valor_reembolso'],
            'motivo' => $motivo,
            'message' => 'Assinatura cancelada com sucesso'
        ];
    }

    /**
     * Obtém histórico completo de alterações de uma assinatura
     * 
     * @param int $idassinatura ID da assinatura
     * @param string $dataInicio Data de início (opcional)
     * @param string $dataFim Data de fim (opcional)
     * @return array
     */
    public function obterHistoricoAlteracoes($idassinatura, $dataInicio = null, $dataFim = null)
    {
        // Usar período do último ano se não informado
        if (!$dataInicio) {
            $dataInicio = date('Y-m-d', strtotime('-1 year'));
        }
        if (!$dataFim) {
            $dataFim = date('Y-m-d');
        }

        // Validar assinatura
        $assinatura = $this->assinaturasModel->buscarPorId($idassinatura);
        if (!$assinatura) {
            throw new \Exception("Assinatura não encontrada");
        }

        $resultado = \core\Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim
            ],
            'assinaturas_avancado/select_historico_precos_assinatura',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new \Exception($resultado['error']);
        }

        return [
            'idassinatura' => $idassinatura,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'total_alteracoes' => count($resultado['retorno']),
            'alteracoes' => $resultado['retorno']
        ];
    }
}
