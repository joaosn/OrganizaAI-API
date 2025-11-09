<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de histórico de preços
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class PrecosHistoricoModel extends Model {

    /**
     * Lista histórico de preços com filtros
     */
    public function listarTodas($filtros = []) {
        $params = [
            'tipo_referencia' => $filtros['tipo_referencia'] ?? null,
            'id_referencia' => $filtros['id_referencia'] ?? null,
            'data_inicio' => $filtros['data_inicio'] ?? null,
            'data_fim' => $filtros['data_fim'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'precos_historico/select_all',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Busca histórico por ID
     */
    public function buscarPorId($idpreco_historico) {
        $resultado = Database::switchParams(
            ['idpreco_historico' => $idpreco_historico],
            'precos_historico/select_by_id',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }

    /**
     * Lista histórico por referência (sistema ou plano)
     */
    public function listarPorReferencia($tipo_referencia, $id_referencia) {
        $resultado = Database::switchParams(
            [
                'tipo_referencia' => $tipo_referencia,
                'id_referencia' => $id_referencia
            ],
            'precos_historico/select_by_referencia',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Lista alterações recentes nos últimos X dias
     */
    public function listarRecentes($dias = 30, $limit = 100) {
        $resultado = Database::switchParams(
            [
                'dias' => $dias,
                'limit' => $limit
            ],
            'precos_historico/select_recent',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Registra alteração de preço
     */
    public function registrarAlteracao($dados) {
        $params = [
            'tipo_referencia' => $dados['tipo_referencia'],
            'id_referencia' => $dados['id_referencia'],
            'campo_alterado' => $dados['campo_alterado'],
            'valor_anterior' => $dados['valor_anterior'] ?? null,
            'valor_novo' => $dados['valor_novo'] ?? null,
            'aliquota_anterior' => $dados['aliquota_anterior'] ?? null,
            'aliquota_nova' => $dados['aliquota_nova'] ?? null,
            'iduser_alteracao' => $dados['iduser_alteracao'],
            'motivo' => $dados['motivo'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'precos_historico/insert',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }

    /**
     * Limpa registros antigos (mais de X dias)
     */
    public function limparAntigos($dias_retencao = 365) {
        $resultado = Database::switchParams(
            ['dias_retencao' => $dias_retencao],
            'precos_historico/cleanup_old',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }
}