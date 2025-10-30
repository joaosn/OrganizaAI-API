<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de relatórios
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class RelatoriosModel extends Model {

    /**
     * Obtém resumo de assinaturas
     */
    public function assinaturasResumo($filtros = []) {
        $params = [
            'status' => $filtros['status'] ?? null,
            'idcliente' => $filtros['idcliente'] ?? null,
            'idsistema' => $filtros['idsistema'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'relatorios/assinaturas_resumo',
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
     * Obtém total mensal de assinaturas
     */
    public function totalMensal($filtros = []) {
        $params = [
            'data_inicio' => $filtros['data_inicio'] ?? null,
            'data_fim' => $filtros['data_fim'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'relatorios/assinaturas_total_mensal',
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
     * Obtém sistemas mais vendidos
     */
    public function sistemasMaisVendidos() {
        $resultado = Database::switchParams(
            [],
            'relatorios/sistemas_vendidos',
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
     * Obtém clientes ativos
     */
    public function clientesAtivos() {
        $resultado = Database::switchParams(
            [],
            'relatorios/clientes_ativos',
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
     * Obtém receita por período
     */
    public function receitaPeriodo() {
        $resultado = Database::switchParams(
            [],
            'relatorios/receita_periodo',
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
     * Obtém estatísticas do dashboard
     */
    public function dashboardStats() {
        $resultado = Database::switchParams(
            [],
            'relatorios/dashboard_stats',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }
}