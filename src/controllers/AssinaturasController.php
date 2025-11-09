<?php

namespace src\controllers;

use \core\Controller as ctrl;
use src\handlers\AssinaturasHandler;

class AssinaturasController extends ctrl
{
    public function index()
    {
        $this->render('assinaturas');
    }

    /**
     * Lista todas as assinaturas (API)
     * GET /api/assinaturas/listar
     */
    public function listar()
    {
        try {
            $handler = new AssinaturasHandler();
            $resultado = $handler->listarAssinaturas();
            
            ctrl::response(['result' => $resultado['data']], 200);
        } catch (\Exception $e) {
            ctrl::rejectResponse($e);
        }
    }
}
