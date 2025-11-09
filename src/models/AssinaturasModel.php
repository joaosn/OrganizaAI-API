<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de assinaturas
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class AssinaturasModel extends Model {

    /**
     * Lista todas as assinaturas com filtros e paginação
     */
    public function listarTodas($filtros = []) {
        $params = [
            'status' => $filtros['status'] ?? null,
            'idcliente' => $filtros['idcliente'] ?? null,
            'idsistema' => $filtros['idsistema'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/select_all',
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
     * Conta total de assinaturas com filtros
     */
    public function contarTodas($filtros = []) {
        $params = [
            'status' => $filtros['status'] ?? null,
            'idcliente' => $filtros['idcliente'] ?? null,
            'idsistema' => $filtros['idsistema'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/count_all',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0]['total'] ?? 0;
    }

    /**
     * Busca assinatura por ID
     */
    public function buscarPorId($idassinatura) {
        $resultado = Database::switchParams(
            ['idassinatura' => $idassinatura],
            'assinaturas/select_by_id',
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
     * Lista assinaturas por cliente
     */
    public function listarPorCliente($idcliente, $filtros = []) {
        $params = [
            'idcliente' => $idcliente,
            'status' => $filtros['status'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/select_by_cliente',
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
     * Lista assinaturas por sistema
     */
    public function listarPorSistema($idsistema, $filtros = []) {
        $params = [
            'idsistema' => $idsistema,
            'status' => $filtros['status'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/select_by_sistema',
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
     * Lista assinaturas ativas
     */
    public function listarAtivas() {
        $resultado = Database::switchParams(
            [],
            'assinaturas/select_ativas',
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
     * Lista assinaturas vencendo nos próximos X dias
     */
    public function listarVencendo($dias = 7) {
        $params = [
            'dias' => $dias,
            'dia_vencimento' => date('d')
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/select_vencendo',
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
     * Insere nova assinatura
     */
    public function inserir($dados) {
        $params = [
            'idcliente' => $dados['idcliente'],
            'idsistema' => $dados['idsistema'],
            'idplano' => $dados['idplano'] ?? null,
            'ciclo_cobranca' => $dados['ciclo_cobranca'],
            'dia_vencimento' => $dados['dia_vencimento'],
            'data_inicio' => $dados['data_inicio'],
            'data_fim' => $dados['data_fim'] ?? null,
            'status' => $dados['status'] ?? 'ativa',
            'preco_sem_imposto' => $dados['preco_sem_imposto'] ?? null,
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/insert',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return Database::getInstance()->lastInsertId();
    }

    /**
     * Atualiza assinatura existente
     */
    public function atualizar($idassinatura, $dados) {
        $params = [
            'idassinatura' => $idassinatura,
            'idcliente' => $dados['idcliente'],
            'idsistema' => $dados['idsistema'],
            'idplano' => $dados['idplano'] ?? null,
            'ciclo_cobranca' => $dados['ciclo_cobranca'],
            'dia_vencimento' => $dados['dia_vencimento'],
            'data_inicio' => $dados['data_inicio'],
            'data_fim' => $dados['data_fim'] ?? null,
            'status' => $dados['status'],
            'preco_sem_imposto' => $dados['preco_sem_imposto'] ?? null,
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas/update',
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
     * Atualiza apenas o status da assinatura
     */
    public function atualizarStatus($idassinatura, $status) {
        $resultado = Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'status' => $status
            ],
            'assinaturas/update_status',
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
     * Exclui assinatura
     */
    public function excluir($idassinatura) {
        $resultado = Database::switchParams(
            ['idassinatura' => $idassinatura],
            'assinaturas/delete',
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