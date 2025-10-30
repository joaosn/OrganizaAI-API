<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de clientes
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class ClientesModel extends Model {

    /**
     * Lista todos os clientes com filtros e paginação
     */
    public function listarTodos($filtros = []) {
        $params = [
            'ativo' => $filtros['ativo'] ?? null,
            'tipo_pessoa' => $filtros['tipo_pessoa'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes/select_all',
            true,   // Executar
            false,  // Sem log
            false   // Sem transação (SELECT)
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Conta total de clientes com filtros
     */
    public function contarTodos($filtros = []) {
        $params = [
            'ativo' => $filtros['ativo'] ?? null,
            'tipo_pessoa' => $filtros['tipo_pessoa'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes/count_all',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0]['total'] ?? 0;
    }

    /**
     * Busca cliente por ID
     */
    public function buscarPorId($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes/select_by_id',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }

    /**
     * Busca cliente por CPF/CNPJ
     */
    public function buscarPorCpfCnpj($cpf_cnpj) {
        $resultado = Database::switchParams(
            ['cpf_cnpj' => $cpf_cnpj],
            'clientes/select_by_cpf_cnpj',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }

    /**
     * Pesquisa clientes por termo
     */
    public function pesquisar($termo, $filtros = []) {
        $params = [
            'termo' => $termo,
            'ativo' => $filtros['ativo'] ?? null,
            'tipo_pessoa' => $filtros['tipo_pessoa'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes/search',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'];
    }

    /**
     * Insere novo cliente
     */
    public function inserir($dados) {
        $params = [
            'tipo_pessoa' => $dados['tipo_pessoa'],
            'nome' => $dados['nome'],
            'nome_fantasia' => $dados['nome_fantasia'] ?? null,
            'cpf_cnpj' => $dados['cpf_cnpj'],
            'ie_rg' => $dados['ie_rg'] ?? null,
            'im' => $dados['im'] ?? null,
            'email' => $dados['email'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'ativo' => $dados['ativo'] ?? 1
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes/insert',
            true,   // Executar
            true,   // Com log
            true    // Com transação
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        // Retorna o ID do último insert usando PDO
        return Database::getInstance()->lastInsertId();
    }

    /**
     * Atualiza cliente existente
     */
    public function atualizar($idcliente, $dados) {
        $params = [
            'idcliente' => $idcliente,
            'tipo_pessoa' => $dados['tipo_pessoa'],
            'nome' => $dados['nome'],
            'nome_fantasia' => $dados['nome_fantasia'] ?? null,
            'cpf_cnpj' => $dados['cpf_cnpj'],
            'ie_rg' => $dados['ie_rg'] ?? null,
            'im' => $dados['im'] ?? null,
            'email' => $dados['email'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'ativo' => $dados['ativo']
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes/update',
            true,
            true,   // Com log
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }

    /**
     * Verifica se cliente pode ser excluído (sem assinaturas ativas)
     */
    public function podeExcluir($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes/check_before_delete',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        $assinaturas_ativas = $resultado['retorno'][0]['assinaturas_ativas'] ?? 0;
        return $assinaturas_ativas == 0;
    }

    /**
     * Exclui cliente
     */
    public function excluir($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes/delete',
            true,
            true,   // Com log
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }
}