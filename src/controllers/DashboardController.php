<?php

namespace src\controllers;

use \core\Controller as ctrl;
use src\handlers\RelatoriosHandler;

/**
 * DashboardController - Responsável por exibir estatísticas
 * ✅ ARQUITETURA CORRETA: Controller → Handler → Model
 */
class DashboardController extends ctrl
{
    /**
     * Renderiza o dashboard
     * GET /dashboard (privado = true)
     */
    public function index()
    {
        $this->render('dashboard');
    }

    /**
     * Obtém estatísticas do dashboard (API)
     * GET /api/dashboard/stats
     * ✅ ARQUITETURA CORRETA: Controller → Handler → Model
     * ✅ Dashboard GERAL - mostra dados de TODOS os sistemas
     */
    public function obterEstatisticas()
    {
        try {
            // Filtro opcional por sistema via query string (?idsistema=123)
            $idsistemaQS = filter_input(INPUT_GET, 'idsistema', FILTER_VALIDATE_INT);
            $idsistema = $idsistemaQS ?: null; // null = geral (todos)
            
            // ✅ Controller → Handler → Model
            $handler = new RelatoriosHandler();
            $dados = $handler->obterEstatisticasDashboard();

            // Retorna os dados
            return self::response($dados, 200);
        } catch (\Exception $e) {
            // self::log('Erro ao obter estatísticas do dashboard: ' . $e->getMessage()); // Desativado para evitar erro de método inexistente
            return self::rejectResponse($e);
        }
    }
}
