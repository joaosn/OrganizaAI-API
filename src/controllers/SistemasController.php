<?php

namespace src\controllers;

use core\Controller as ctrl;
use Exception;
use src\handlers\SistemasHandler;

/**
 * SistemasController - Responsável por gerenciar sistemas/clientes da API
 */
class SistemasController extends ctrl
{

    /**
     * Renderiza a página de listagem de sistemas
     * GET /sistemas
     */
    public function index()
    {
        $this->render('sistemas');
    }

    // Os métodos de API (listarSistemas, obterSistema, criarSistema, atualizarSistema, deletarSistema, regenerarChaveApi)
    // são mantidos do MailJZTech, pois a API do OrganizaAI-API deve ter endpoints semelhantes.
    // A lógica de front-end (paginaCriar, paginaEditar) é removida, pois não é relevante para o OrganizaAI-API.

    /**
     * Lista todos os sistemas
     * 
     * GET /api/sistemas/listar
     */
    public function listar()
    {
        try {
            $handler = new SistemasHandler();
            $resultado = $handler->listarSistemas();
            
            ctrl::response(['result' => $resultado['data']], 200);
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }
}
