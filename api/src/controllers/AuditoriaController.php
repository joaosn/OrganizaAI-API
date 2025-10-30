<?php

namespace src\controllers;

use src\handlers\AuditoriaHandler;
use src\models\PrecosHistoricoModel;
use core\Controller;
use Exception;

/**
 * Controller para gerenciamento de auditoria de preços
 * Responsabilidade: Receber requisições HTTP e retornar respostas JSON
 */
class AuditoriaController extends Controller {

    private $auditoriaHandler;

    const REGISTRAR_CAMPOS = ['tipo_referencia', 'id_referencia', 'campo_alterado', 'iduser_alteracao'];

    public function __construct() {
        parent::__construct();
        $this->auditoriaHandler = new AuditoriaHandler();
    }

    /**
     * GET /auditoria/precos
     * Lista histórico de preços com filtros
     */
    public function listarHistorico() {
        try {
            $model = new PrecosHistoricoModel();

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = ($page - 1) * $limit;

            $filtros = [
                'tipo_referencia' => $_GET['tipo'] ?? null,
                'id_referencia' => $_GET['id'] ?? null,
                'data_inicio' => $_GET['data_inicio'] ?? null,
                'data_fim' => $_GET['data_fim'] ?? null,
                'limit' => $limit,
                'offset' => $offset
            ];

            $historico = $model->listarTodas($filtros);

            Controller::response([
                'historico' => $historico,
                'total' => count($historico),
                'pagina' => $page,
                'limite' => $limit
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /auditoria/precos/{id}
     * Busca alteração específica
     */
    public function buscarAlteracao($args) {
        try {
            $idpreco_historico = $args['id'];
            $model = new PrecosHistoricoModel();

            $alteracao = $model->buscarPorId($idpreco_historico);

            if (!$alteracao) {
                Controller::response(['mensagem' => 'Alteração não encontrada'], 404);
                return;
            }

            Controller::response($alteracao, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /auditoria/precos/referencia/{tipo}/{id}
     * Histórico completo de um item
     */
    public function historicoCompleto($args) {
        try {
            $tipo_referencia = $args['tipo'];
            $id_referencia = $args['id'];

            $result = $this->auditoriaHandler->obterHistoricoCompleto(
                $tipo_referencia,
                $id_referencia
            );

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /auditoria/precos/recentes
     * Alterações dos últimos X dias
     */
    public function recentes() {
        try {
            $dias = isset($_GET['dias']) ? (int)$_GET['dias'] : 30;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

            $result = $this->auditoriaHandler->obterRecentes($dias, $limit);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /auditoria/precos/relatorio/periodo
     * Relatório por período
     */
    public function relatorioPeriodo() {
        try {
            $data_inicio = $_GET['data_inicio'] ?? null;
            $data_fim = $_GET['data_fim'] ?? null;
            $tipo = $_GET['tipo'] ?? null;

            if (!$data_inicio || !$data_fim) {
                Controller::response(['mensagem' => 'data_inicio e data_fim são obrigatórios'], 400);
                return;
            }

            $result = $this->auditoriaHandler->gerarRelatorioPeriodo(
                $data_inicio,
                $data_fim,
                $tipo
            );

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /auditoria/precos/registrar
     * Registra alteração de preço
     */
    public function registrarAlteracao() {
        try {
            $data = Controller::getBody();

            Controller::verificarCamposVazios($data, self::REGISTRAR_CAMPOS);

            $result = $this->auditoriaHandler->registrarAlteracao($data);

            Controller::response($result, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /auditoria/precos/cleanup
     * Limpa registros antigos
     */
    public function cleanup() {
        try {
            $data = Controller::getBody();

            $dias_retencao = $data['dias_retencao'] ?? 365;

            $result = $this->auditoriaHandler->limparDadosAntigos($dias_retencao);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}