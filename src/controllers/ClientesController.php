<?php

namespace src\controllers;

use \core\Controller as ctrl;

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
            // TODO: Implementar a chamada ao Handler de Clientes para listar os dados
            // Por enquanto, retorna um mock para teste de front-end
            $clientes = [
                ['idcliente' => 1, 'nome_razao' => 'Cliente Teste 1', 'cpf_cnpj' => '123.456.789-00', 'status' => 'Ativo'],
                ['idcliente' => 2, 'nome_razao' => 'Cliente Teste 2', 'cpf_cnpj' => '987.654.321-00', 'status' => 'Inativo'],
            ];
            
            ctrl::response(['result' => $clientes], 200);
        } catch (\Exception $e) {
            ctrl::rejectResponse($e);
        }
    }
}
