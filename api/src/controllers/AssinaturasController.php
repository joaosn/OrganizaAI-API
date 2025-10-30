<?php

namespace src\controllers;

use src\handlers\AssinaturasHandler;
use src\models\AssinaturasModel;
use src\models\AssinaturasAddonsModel;
use core\Controller;
use Exception;

/**
 * Controller para gerenciamento de assinaturas
 * Responsabilidade: Receber requisições HTTP e retornar respostas JSON
 */
class AssinaturasController extends Controller {

    private $assinaturasHandler;

    // ✅ Constantes de validação (campos obrigatórios)
    const CRIAR_CAMPOS = ['idcliente', 'idsistema', 'ciclo_cobranca', 'dia_vencimento', 'data_inicio'];
    const ATUALIZAR_CAMPOS = ['idcliente', 'idsistema', 'ciclo_cobranca', 'dia_vencimento', 'data_inicio', 'status'];
    const ALTERAR_STATUS_CAMPOS = ['status'];
    const ADICIONAR_ADDON_CAMPOS = ['idassinatura', 'idaddon'];
    const ATUALIZAR_ADDON_CAMPOS = ['idassinatura', 'idaddon'];

    public function __construct() {
        parent::__construct();
        $this->assinaturasHandler = new AssinaturasHandler();
    }

    /**
     * GET /assinaturas
     * Lista todas as assinaturas com paginação e filtros
     */
    public function listarAssinaturas() {
        try {
            $model = new AssinaturasModel();

            // Obtém parâmetros de paginação e filtros
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = ($page - 1) * $limit;

            $filtros = [
                'status' => $_GET['status'] ?? null,
                'idcliente' => $_GET['idcliente'] ?? null,
                'idsistema' => $_GET['idsistema'] ?? null,
                'limit' => $limit,
                'offset' => $offset
            ];

            // Lista assinaturas
            $assinaturas = $model->listarTodas($filtros);
            
            // Conta total
            $total = $model->contarTodas($filtros);

            Controller::response([
                'assinaturas' => $assinaturas,
                'total' => $total,
                'pagina' => $page,
                'limite' => $limit,
                'total_paginas' => ceil($total / $limit)
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/{idassinatura}
     * Busca assinatura por ID
     */
    public function buscarAssinatura($args) {
        try {
            $idassinatura = $args['idassinatura'];
            $model = new AssinaturasModel();

            $assinatura = $model->buscarPorId($idassinatura);

            if (!$assinatura) {
                Controller::response(['mensagem' => 'Assinatura não encontrada'], 404);
                return;
            }

            Controller::response($assinatura, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/cliente/{idcliente}
     * Lista assinaturas de um cliente
     */
    public function listarPorCliente($args) {
        try {
            $idcliente = $args['idcliente'];
            $model = new AssinaturasModel();

            $status = $_GET['status'] ?? null;
            $filtros = ['status' => $status];

            $assinaturas = $model->listarPorCliente($idcliente, $filtros);

            Controller::response([
                'assinaturas' => $assinaturas,
                'total' => count($assinaturas)
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/sistema/{idsistema}
     * Lista assinaturas de um sistema
     */
    public function listarPorSistema($args) {
        try {
            $idsistema = $args['idsistema'];
            $model = new AssinaturasModel();

            $status = $_GET['status'] ?? null;
            $filtros = ['status' => $status];

            $assinaturas = $model->listarPorSistema($idsistema, $filtros);

            Controller::response([
                'assinaturas' => $assinaturas,
                'total' => count($assinaturas)
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/ativas
     * Lista todas as assinaturas ativas
     */
    public function listarAtivas() {
        try {
            $model = new AssinaturasModel();
            $assinaturas = $model->listarAtivas();

            Controller::response([
                'assinaturas' => $assinaturas,
                'total' => count($assinaturas)
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/vencendo
     * Lista assinaturas vencendo nos próximos X dias
     */
    public function listarVencendo() {
        try {
            $model = new AssinaturasModel();
            $dias = isset($_GET['dias']) ? (int)$_GET['dias'] : 7;

            $assinaturas = $model->listarVencendo($dias);

            Controller::response([
                'assinaturas' => $assinaturas,
                'total' => count($assinaturas),
                'dias_verificados' => $dias
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas
     * Cria nova assinatura
     */
    public function criarAssinatura() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, self::CRIAR_CAMPOS);

            // Chama handler
            $result = $this->assinaturasHandler->criarAssinatura($data);

            Controller::response($result, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /assinaturas
     * Atualiza assinatura existente
     */
    public function atualizarAssinatura() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, ['idassinatura', ...self::ATUALIZAR_CAMPOS]);

            $idassinatura = $data['idassinatura'];

            // Chama handler
            $result = $this->assinaturasHandler->atualizarAssinatura($idassinatura, $data);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /assinaturas/alterar-status
     * Altera status de uma assinatura
     */
    public function alterarStatus() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, ['idassinatura', 'status']);

            $idassinatura = $data['idassinatura'];
            $novoStatus = $data['status'];

            // Chama handler
            $result = $this->assinaturasHandler->alterarStatus($idassinatura, $novoStatus);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /assinaturas
     * Exclui assinatura
     */
    public function excluirAssinatura() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, ['idassinatura']);

            $idassinatura = $data['idassinatura'];

            // Chama handler
            $result = $this->assinaturasHandler->excluirAssinatura($idassinatura);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas/addon
     * Adiciona add-on a uma assinatura
     */
    public function adicionarAddon() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, self::ADICIONAR_ADDON_CAMPOS);

            $idassinatura = $data['idassinatura'];
            $idaddon = $data['idaddon'];
            $quantidade = $data['quantidade'] ?? 1;
            $preco_unitario = $data['preco_unitario'] ?? null;

            // Chama handler
            $result = $this->assinaturasHandler->adicionarAddon(
                $idassinatura,
                $idaddon,
                $quantidade,
                $preco_unitario
            );

            Controller::response($result, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /assinaturas/addon
     * Atualiza add-on de uma assinatura
     */
    public function atualizarAddon() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, self::ATUALIZAR_ADDON_CAMPOS);

            $idassinatura = $data['idassinatura'];
            $idaddon = $data['idaddon'];
            $quantidade = $data['quantidade'] ?? null;
            $preco_unitario = $data['preco_unitario'] ?? null;

            // Chama handler
            $result = $this->assinaturasHandler->atualizarAddon(
                $idassinatura,
                $idaddon,
                $quantidade,
                $preco_unitario
            );

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /assinaturas/addon
     * Remove add-on de uma assinatura
     */
    public function removerAddon() {
        try {
            $data = Controller::getBody();

            // Valida campos obrigatórios
            Controller::verificarCamposVazios($data, ['idassinatura', 'idaddon']);

            $idassinatura = $data['idassinatura'];
            $idaddon = $data['idaddon'];

            // Chama handler
            $result = $this->assinaturasHandler->removerAddon($idassinatura, $idaddon);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/custo-total/{idassinatura}
     * Calcula custo total da assinatura
     */
    public function calcularCustoTotal($args) {
        try {
            $idassinatura = $args['idassinatura'];

            // Chama handler
            $result = $this->assinaturasHandler->calcularCustoTotal($idassinatura);

            Controller::response($result, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas/{idassinatura}/addons
     * Lista add-ons de uma assinatura
     */
    public function listarAddons($args) {
        try {
            $idassinatura = $args['idassinatura'];
            $model = new AssinaturasAddonsModel();

            // Valida que assinatura existe
            $assinaturasModel = new AssinaturasModel();
            $assinatura = $assinaturasModel->buscarPorId($idassinatura);
            if (!$assinatura) {
                Controller::response(['mensagem' => 'Assinatura não encontrada'], 404);
                return;
            }

            $addons = $model->listarPorAssinatura($idassinatura);

            Controller::response([
                'addons' => $addons,
                'total' => count($addons)
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}