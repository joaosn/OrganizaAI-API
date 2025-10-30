<?php

namespace src\controllers;

use src\handlers\ClientesHandler;
use src\models\ClientesEnderecosModel;
use src\models\ClientesContatosModel;
use core\Controller;
use Exception;

/**
 * Controller para endpoints de clientes
 * Responsabilidade: Receber requisição, chamar handler, retornar resposta
 */
class ClientesController extends Controller {
    
    private $clientesHandler;

    // Constantes para validação de campos obrigatórios
    const CRIAR_CAMPOS = ['tipo_pessoa', 'nome', 'cpf_cnpj'];
    const ATUALIZAR_CAMPOS = ['tipo_pessoa', 'nome', 'cpf_cnpj', 'ativo'];
    const ENDERECO_CAMPOS = ['idcliente', 'tipo', 'logradouro', 'cidade', 'uf'];
    const CONTATO_CAMPOS = ['idcliente', 'nome'];
    const PRINCIPAL_CAMPOS = ['id'];

    public function __construct() {
        parent::__construct();
        $this->clientesHandler = new ClientesHandler();
    }

    /**
     * GET /clientes
     * Lista clientes com filtros
     */
    public function listarClientes($args = []) {
        try {
            // Parâmetros de query string ou args da URL
            $filtros = [
                'ativo' => $_GET['ativo'] ?? null,
                'tipo_pessoa' => $_GET['tipo_pessoa'] ?? null,
                'limit' => (int)($_GET['limit'] ?? 50),
                'offset' => (int)($_GET['offset'] ?? 0)
            ];

            // Remove valores nulos
            $filtros = array_filter($filtros, function($value) {
                return $value !== null && $value !== '';
            });

            $resultado = $this->clientesHandler->listarClientes($filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /clientes/{id}
     * Busca cliente por ID
     */
    public function buscarCliente($args) {
        try {
            $idcliente = $args['id'] ?? null;
            
            if (!$idcliente) {
                throw new Exception("ID do cliente é obrigatório");
            }

            $resultado = $this->clientesHandler->buscarClienteCompleto($idcliente);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /clientes/cpf/{cpf_cnpj}
     * Busca cliente por CPF/CNPJ
     */
    public function buscarPorCpfCnpj($args) {
        try {
            $cpf_cnpj = $args['cpf_cnpj'] ?? null;
            
            if (!$cpf_cnpj) {
                throw new Exception("CPF/CNPJ é obrigatório");
            }

            $resultado = $this->clientesHandler->buscarPorCpfCnpj($cpf_cnpj);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /clientes/pesquisar
     * Pesquisa clientes por termo
     */
    public function pesquisarClientes() {
        try {
            $termo = $_GET['termo'] ?? '';
            
            if (empty($termo)) {
                throw new Exception("Termo de pesquisa é obrigatório");
            }

            $filtros = [
                'ativo' => $_GET['ativo'] ?? null,
                'tipo_pessoa' => $_GET['tipo_pessoa'] ?? null,
                'limit' => (int)($_GET['limit'] ?? 50),
                'offset' => (int)($_GET['offset'] ?? 0)
            ];

            $resultado = $this->clientesHandler->pesquisarClientes($termo, $filtros);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /clientes
     * Cria novo cliente
     */
    public function criarCliente() {
        try {
            $dados = Controller::getBody();
            
            // Valida campos obrigatórios
            Controller::verificarCamposVazios($dados, self::CRIAR_CAMPOS);

            $resultado = $this->clientesHandler->criarCliente($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /clientes
     * Atualiza cliente existente
     */
    public function atualizarCliente() {
        try {
            $dados = Controller::getBody();
            
            // Valida campos obrigatórios (incluindo idcliente)
            Controller::verificarCamposVazios($dados, array_merge(['idcliente'], self::ATUALIZAR_CAMPOS));

            $idcliente = $dados['idcliente'];
            unset($dados['idcliente']); // Remove ID dos dados para não tentar atualizar

            $resultado = $this->clientesHandler->atualizarCliente($idcliente, $dados);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /clientes
     * Exclui cliente
     */
    public function excluirCliente() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idcliente'])) {
                throw new Exception("ID do cliente é obrigatório");
            }

            $resultado = $this->clientesHandler->excluirCliente($dados['idcliente']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /clientes/{id}/enderecos
     * Lista endereços do cliente
     */
    public function listarEnderecos($args) {
        try {
            $idcliente = $args['id'] ?? null;
            
            if (!$idcliente) {
                throw new Exception("ID do cliente é obrigatório");
            }

            $enderecosModel = new ClientesEnderecosModel();
            $enderecos = $enderecosModel->listarPorCliente($idcliente);

            Controller::response([
                'success' => true,
                'data' => $enderecos
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /clientes/enderecos
     * Adiciona endereço ao cliente
     */
    public function adicionarEndereco() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, self::ENDERECO_CAMPOS);

            $resultado = $this->clientesHandler->adicionarEndereco($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /clientes/enderecos
     * Atualiza endereço
     */
    public function atualizarEndereco() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, array_merge(['idendereco'], self::ENDERECO_CAMPOS));

            $enderecosModel = new ClientesEnderecosModel();
            $idendereco = $dados['idendereco'];
            unset($dados['idendereco']);

            $enderecosModel->atualizar($idendereco, $dados);

            Controller::response([
                'success' => true,
                'message' => 'Endereço atualizado com sucesso'
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /clientes/enderecos
     * Remove endereço
     */
    public function removerEndereco() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idendereco'])) {
                throw new Exception("ID do endereço é obrigatório");
            }

            $enderecosModel = new ClientesEnderecosModel();
            $enderecosModel->excluir($dados['idendereco']);

            Controller::response([
                'success' => true,
                'message' => 'Endereço removido com sucesso'
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /clientes/enderecos/principal
     * Define endereço como principal
     */
    public function definirEnderecoPrincipal() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, ['idendereco']);

            $resultado = $this->clientesHandler->definirEnderecoPrincipal($dados['idendereco']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /clientes/{id}/contatos
     * Lista contatos do cliente
     */
    public function listarContatos($args) {
        try {
            $idcliente = $args['id'] ?? null;
            
            if (!$idcliente) {
                throw new Exception("ID do cliente é obrigatório");
            }

            $contatosModel = new ClientesContatosModel();
            $contatos = $contatosModel->listarPorCliente($idcliente);

            Controller::response([
                'success' => true,
                'data' => $contatos
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /clientes/contatos
     * Adiciona contato ao cliente
     */
    public function adicionarContato() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, self::CONTATO_CAMPOS);

            $resultado = $this->clientesHandler->adicionarContato($dados);

            Controller::response($resultado, 201);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /clientes/contatos
     * Atualiza contato
     */
    public function atualizarContato() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, array_merge(['idcontato'], self::CONTATO_CAMPOS));

            $contatosModel = new ClientesContatosModel();
            $idcontato = $dados['idcontato'];
            unset($dados['idcontato']);

            $contatosModel->atualizar($idcontato, $dados);

            Controller::response([
                'success' => true,
                'message' => 'Contato atualizado com sucesso'
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * DELETE /clientes/contatos
     * Remove contato
     */
    public function removerContato() {
        try {
            $dados = Controller::getBody();
            
            if (empty($dados['idcontato'])) {
                throw new Exception("ID do contato é obrigatório");
            }

            $contatosModel = new ClientesContatosModel();
            $contatosModel->excluir($dados['idcontato']);

            Controller::response([
                'success' => true,
                'message' => 'Contato removido com sucesso'
            ], 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * PUT /clientes/contatos/principal
     * Define contato como principal
     */
    public function definirContatoPrincipal() {
        try {
            $dados = Controller::getBody();
            
            Controller::verificarCamposVazios($dados, ['idcontato']);

            $resultado = $this->clientesHandler->definirContatoPrincipal($dados['idcontato']);

            Controller::response($resultado, 200);
        } catch (Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}