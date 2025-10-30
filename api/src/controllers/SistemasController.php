<?php

namespace src\controllers;

use src\handlers\SistemasHandler;
use src\models\SistemasplanosModel;
use src\models\SistemasAddonsModel;
use core\Controller;
use Exception;

/**
 * Controller para endpoints de sistemas
 * Responsabilidade: Receber requisição, chamar handler, retornar resposta
 */
class SistemasController extends Controller {
    
    private $sistemasHandler;

    // Constantes para validação de campos obrigatórios
    const SISTEMA_CAMPOS = ['nome'];
    const PLANO_CAMPOS = ['idsistema', 'nome', 'ciclo_cobranca', 'preco_base_sem_imposto', 'aliquota_imposto_percent'];
    const ADDON_CAMPOS = ['idsistema', 'nome', 'preco_sem_imposto', 'aliquota_imposto_percent'];

    public function __construct() {
        parent::__construct();
        $this->sistemasHandler = new SistemasHandler();
    }

    /**
     * GET /sistemas
     * Lista sistemas com filtros
     */
    public function listarSistemas($args = []) {
        try {
            $filtros = [
                'ativo' => $_GET['ativo'] ?? null,
                'categoria' => $_GET['categoria'] ?? null,
                'limit' => (int)($_GET['limit'] ?? 50),
                'offset' => (int)($_GET['offset'] ?? 0)
            ];

            // Remove valores nulos
            $filtros = array_filter($filtros, function($value) {
                return $value !== null && $value !== '';
            });

            $resultado = $this->sistemasHandler->listarSistemas($filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/ativos
     * Lista apenas sistemas ativos
     */
    public function listarSistemasAtivos() {
        try {
            $resultado = $this->sistemasHandler->listarSistemasAtivos();

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/{id}
     * Busca sistema por ID
     */
    public function buscarSistema($args) {
        try {
            $idsistema = $args['id'] ?? null;
            
            if (!$idsistema) {
                throw new Exception("ID do sistema é obrigatório");
            }

            $resultado = $this->sistemasHandler->buscarSistemaCompleto($idsistema);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/pesquisar
     * Pesquisa sistemas por termo
     */
    public function pesquisarSistemas() {
        try {
            $termo = $_GET['termo'] ?? '';
            
            if (empty($termo)) {
                throw new Exception("Termo de pesquisa é obrigatório");
            }

            $filtros = [
                'ativo' => $_GET['ativo'] ?? null,
                'categoria' => $_GET['categoria'] ?? null,
                'limit' => (int)($_GET['limit'] ?? 50),
                'offset' => (int)($_GET['offset'] ?? 0)
            ];

            $resultado = $this->sistemasHandler->pesquisarSistemas($termo, $filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /sistemas
     * Cria novo sistema
     */
    public function criarSistema() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, self::SISTEMA_CAMPOS);

            $resultado = $this->sistemasHandler->criarSistema($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /sistemas
     * Atualiza sistema existente
     */
    public function atualizarSistema() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, array_merge(['idsistema'], self::SISTEMA_CAMPOS, ['ativo']));

            $idsistema = $dados['idsistema'];
            unset($dados['idsistema']);

            $resultado = $this->sistemasHandler->atualizarSistema($idsistema, $dados);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /sistemas
     * Exclui sistema
     */
    public function excluirSistema() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idsistema'])) {
                throw new Exception("ID do sistema é obrigatório");
            }

            $resultado = $this->sistemasHandler->excluirSistema($dados['idsistema']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/{id}/planos
     * Lista planos do sistema
     */
    public function listarPlanos($args) {
        try {
            $idsistema = $args['id'] ?? null;
            
            if (!$idsistema) {
                throw new Exception("ID do sistema é obrigatório");
            }

            $filtros = [
                'ativo' => $_GET['ativo'] ?? null
            ];

            $resultado = $this->sistemasHandler->listarPlanosSistema($idsistema, $filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/planos/ativos
     * Lista todos os planos ativos
     */
    public function listarPlanosAtivos() {
        try {
            $planosModel = new SistemasplanosModel();
            $planos = $planosModel->listarAtivos();

            Controller::response([
                'success' => true,
                'data' => $planos
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/planos/{id}
     * Busca plano por ID
     */
    public function buscarPlano($args) {
        try {
            $idplano = $args['id'] ?? null;
            
            if (!$idplano) {
                throw new Exception("ID do plano é obrigatório");
            }

            $planosModel = new SistemasplanosModel();
            $plano = $planosModel->buscarPorId($idplano);

            if (!$plano) {
                throw new Exception("Plano não encontrado");
            }

            Controller::response([
                'success' => true,
                'data' => $plano
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /sistemas/planos
     * Cria plano
     */
    public function criarPlano() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, self::PLANO_CAMPOS);

            $resultado = $this->sistemasHandler->criarPlano($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /sistemas/planos
     * Atualiza plano
     */
    public function atualizarPlano() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, array_merge(['idplano'], self::PLANO_CAMPOS, ['ativo']));

            $idplano = $dados['idplano'];
            unset($dados['idplano']);

            $resultado = $this->sistemasHandler->atualizarPlano($idplano, $dados);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /sistemas/planos
     * Exclui plano
     */
    public function excluirPlano() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idplano'])) {
                throw new Exception("ID do plano é obrigatório");
            }

            $resultado = $this->sistemasHandler->excluirPlano($dados['idplano']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/{id}/addons
     * Lista add-ons do sistema
     */
    public function listarAddons($args) {
        try {
            $idsistema = $args['id'] ?? null;
            
            if (!$idsistema) {
                throw new Exception("ID do sistema é obrigatório");
            }

            $filtros = [
                'ativo' => $_GET['ativo'] ?? null
            ];

            $resultado = $this->sistemasHandler->listarAddonsSistema($idsistema, $filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/addons/ativos
     * Lista todos os add-ons ativos
     */
    public function listarAddonsAtivos() {
        try {
            $addonsModel = new SistemasAddonsModel();
            $addons = $addonsModel->listarAtivos();

            Controller::response([
                'success' => true,
                'data' => $addons
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /sistemas/addons/{id}
     * Busca add-on por ID
     */
    public function buscarAddon($args) {
        try {
            $idaddon = $args['id'] ?? null;
            
            if (!$idaddon) {
                throw new Exception("ID do add-on é obrigatório");
            }

            $addonsModel = new SistemasAddonsModel();
            $addon = $addonsModel->buscarPorId($idaddon);

            if (!$addon) {
                throw new Exception("Add-on não encontrado");
            }

            Controller::response([
                'success' => true,
                'data' => $addon
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /sistemas/addons
     * Cria add-on
     */
    public function criarAddon() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, self::ADDON_CAMPOS);

            $resultado = $this->sistemasHandler->criarAddon($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /sistemas/addons
     * Atualiza add-on
     */
    public function atualizarAddon() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, array_merge(['idaddon'], self::ADDON_CAMPOS, ['ativo']));

            $idaddon = $dados['idaddon'];
            unset($dados['idaddon']);

            $resultado = $this->sistemasHandler->atualizarAddon($idaddon, $dados);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /sistemas/addons
     * Exclui add-on
     */
    public function excluirAddon() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idaddon'])) {
                throw new Exception("ID do add-on é obrigatório");
            }

            $resultado = $this->sistemasHandler->excluirAddon($dados['idaddon']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}