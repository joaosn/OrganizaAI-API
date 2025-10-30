<?php

namespace src\controllers;

use src\handlers\RelatoriosHandler;
use src\models\RelatoriosModel;
use core\Controller;
use Exception;

/**
 * Controller para gerenciamento de relatórios
 * Responsabilidade: Receber requisições HTTP e retornar respostas JSON
 */
class RelatoriosController extends Controller {

    private $relatoriosHandler;

    public function __construct() {
        parent::__construct();
        $this->relatoriosHandler = new RelatoriosHandler();
    }

    /**
     * GET /relatorios/assinaturas-resumo
     * Relatório resumido de assinaturas
     */
    public function assinaturasResumo() {
        try {
            $filtros = [
                'status' => $_GET['status'] ?? null,
                'idcliente' => $_GET['idcliente'] ?? null,
                'idsistema' => $_GET['idsistema'] ?? null
            ];

            $result = $this->relatoriosHandler->obterResumoAssinaturas($filtros);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /relatorios/receita-mensal
     * Relatório de receita mensal
     */
    public function receitaMensal() {
        try {
            $filtros = [
                'data_inicio' => $_GET['data_inicio'] ?? null,
                'data_fim' => $_GET['data_fim'] ?? null
            ];

            $result = $this->relatoriosHandler->obterReceitaMensal($filtros);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /relatorios/sistemas-vendidos
     * Ranking de sistemas mais vendidos
     */
    public function sistemasVendidos() {
        try {
            $result = $this->relatoriosHandler->obterSistemasVendidos();

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /relatorios/clientes-ativos
     * Relatório de clientes ativos
     */
    public function clientesAtivos() {
        try {
            $model = new RelatoriosModel();
            $clientes = $model->clientesAtivos();

            Controller::response([
                'total' => count($clientes),
                'clientes' => $clientes
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /relatorios/dashboard
     * Dashboard com estatísticas gerais
     */
    public function dashboard() {
        try {
            $result = $this->relatoriosHandler->obterDashboard();

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /relatorios/receita-periodo
     * Receita por período
     */
    public function receitaPeriodo() {
        try {
            $model = new RelatoriosModel();
            $receita = $model->receitaPeriodo();

            $stats = [
                'total_periodos' => count($receita),
                'receita_total_geral' => 0,
                'receita_base_total' => 0,
                'receita_addons_total' => 0,
                'assinaturas_total' => 0
            ];

            foreach ($receita as $periodo) {
                $stats['receita_total_geral'] += $periodo['receita_total'] ?? 0;
                $stats['receita_base_total'] += $periodo['receita_base'] ?? 0;
                $stats['receita_addons_total'] += $periodo['receita_addons'] ?? 0;
                $stats['assinaturas_total'] += $periodo['total_assinaturas'] ?? 0;
            }

            Controller::response([
                'stats' => $stats,
                'periodos' => $receita
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}