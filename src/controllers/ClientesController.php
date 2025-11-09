<?php

namespace src\controllers;

use \core\Controller as ctrl;
use src\handlers\ClientesHandler;

class ClientesController extends ctrl
{
    /**
     * Renderiza a página de listagem de clientes
     * GET /clientes
     */
    public function index()
    {
        $this->render('clientes');
    }

    /**
     * Lista todos os clientes (API)
     * GET /api/clientes/listar
     */
    public function listar()
    {
        try {
            $handler = new ClientesHandler();
            $resultado = $handler->listarClientes();
            
            ctrl::response(['result' => $resultado['data']], 200);
        } catch (\Exception $e) {
            ctrl::rejectResponse($e);
        }
    }
}
