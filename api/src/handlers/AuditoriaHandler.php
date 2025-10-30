<?php

namespace src\handlers;

use src\models\PrecosHistoricoModel;
use core\Database;
use Exception;

/**
 * Handler para lógica de negócios de auditoria de preços
 * Responsabilidade: Validações e orquestração de models
 */
class AuditoriaHandler {

    private $precosHistoricoModel;

    public function __construct() {
        $this->precosHistoricoModel = new PrecosHistoricoModel();
    }

    /**
     * Validações gerais de auditoria
     */
    private function validarAlteracao($dados) {
        // Tipo de referência válido?
        if (!$this->validarTipoReferencia($dados['tipo_referencia'])) {
            throw new Exception("Tipo de referência inválido. Valores permitidos: sistema_plano, sistema_addon, assinatura");
        }

        // ID de referência válido?
        if (!is_numeric($dados['id_referencia']) || $dados['id_referencia'] <= 0) {
            throw new Exception("ID de referência deve ser um número positivo");
        }

        // Campo alterado válido?
        if (empty($dados['campo_alterado'])) {
            throw new Exception("Campo alterado é obrigatório");
        }

        // Pelo menos um valor ou alíquota deve estar presente
        if (
            (empty($dados['valor_anterior']) && empty($dados['valor_novo'])) &&
            (empty($dados['aliquota_anterior']) && empty($dados['aliquota_nova']))
        ) {
            throw new Exception("Deve informar alteração de valor ou alíquota");
        }

        // Preços válidos?
        if (!empty($dados['valor_anterior']) && $dados['valor_anterior'] < 0) {
            throw new Exception("Valor anterior não pode ser negativo");
        }
        if (!empty($dados['valor_novo']) && $dados['valor_novo'] < 0) {
            throw new Exception("Valor novo não pode ser negativo");
        }

        // Alíquotas válidas?
        if (!empty($dados['aliquota_anterior'])) {
            if ($dados['aliquota_anterior'] < 0 || $dados['aliquota_anterior'] > 100) {
                throw new Exception("Alíquota anterior deve estar entre 0 e 100%");
            }
        }
        if (!empty($dados['aliquota_nova'])) {
            if ($dados['aliquota_nova'] < 0 || $dados['aliquota_nova'] > 100) {
                throw new Exception("Alíquota nova deve estar entre 0 e 100%");
            }
        }

        return true;
    }

    /**
     * Valida tipo de referência
     */
    private function validarTipoReferencia($tipo) {
        $tiposValidos = ['sistema_plano', 'sistema_addon', 'assinatura'];
        return in_array($tipo, $tiposValidos);
    }

    /**
     * Registra alteração de preço
     */
    public function registrarAlteracao($dados) {
        try {
            // Validações
            $this->validarAlteracao($dados);

            // Registra
            $this->precosHistoricoModel->registrarAlteracao($dados);

            return [
                'success' => true,
                'message' => 'Alteração de preço registrada com sucesso'
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtém histórico completo de um item
     */
    public function obterHistoricoCompleto($tipo_referencia, $id_referencia) {
        try {
            // Tipo válido?
            if (!$this->validarTipoReferencia($tipo_referencia)) {
                throw new Exception("Tipo de referência inválido");
            }

            // Obtém histórico
            $historico = $this->precosHistoricoModel->listarPorReferencia(
                $tipo_referencia,
                $id_referencia
            );

            return [
                'success' => true,
                'tipo_referencia' => $tipo_referencia,
                'id_referencia' => $id_referencia,
                'total_alteracoes' => count($historico),
                'historico' => $historico
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Obtém alterações recentes
     */
    public function obterRecentes($dias = 30, $limit = 100) {
        try {
            // Valores válidos?
            if (!is_numeric($dias) || $dias <= 0) {
                throw new Exception("Dias deve ser um número positivo");
            }
            if (!is_numeric($limit) || $limit <= 0) {
                throw new Exception("Limit deve ser um número positivo");
            }

            // Obtém recentes
            $recentes = $this->precosHistoricoModel->listarRecentes($dias, $limit);

            return [
                'success' => true,
                'dias_verificados' => $dias,
                'total_alteracoes' => count($recentes),
                'alteracoes' => $recentes
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Faz limpeza de dados antigos
     */
    public function limparDadosAntigos($dias_retencao = 365) {
        try {
            // Valor válido?
            if (!is_numeric($dias_retencao) || $dias_retencao < 30) {
                throw new Exception("Período de retenção mínimo é 30 dias");
            }

            // Limpa
            $this->precosHistoricoModel->limparAntigos($dias_retencao);

            return [
                'success' => true,
                'message' => "Registros com mais de $dias_retencao dias foram removidos"
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Gera relatório de alterações por período
     */
    public function gerarRelatorioPeriodo($data_inicio, $data_fim, $tipo_referencia = null) {
        try {
            // Datas válidas?
            $inicio = strtotime($data_inicio);
            $fim = strtotime($data_fim);

            if (!$inicio || !$fim) {
                throw new Exception("Datas inválidas (formato: YYYY-MM-DD)");
            }

            if ($fim <= $inicio) {
                throw new Exception("Data fim deve ser posterior à data início");
            }

            $filtros = [
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'tipo_referencia' => $tipo_referencia,
                'limit' => 1000,
                'offset' => 0
            ];

            $alteracoes = $this->precosHistoricoModel->listarTodas($filtros);

            // Agrupa por tipo
            $por_tipo = [];
            foreach ($alteracoes as $alt) {
                $tipo = $alt['tipo_referencia'];
                if (!isset($por_tipo[$tipo])) {
                    $por_tipo[$tipo] = [];
                }
                $por_tipo[$tipo][] = $alt;
            }

            return [
                'success' => true,
                'periodo' => [
                    'inicio' => $data_inicio,
                    'fim' => $data_fim
                ],
                'total_alteracoes' => count($alteracoes),
                'agrupado_por_tipo' => $por_tipo
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}