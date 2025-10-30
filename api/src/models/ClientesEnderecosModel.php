<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de endereços dos clientes
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class ClientesEnderecosModel extends Model {

    /**
     * Lista endereços de um cliente
     */
    public function listarPorCliente($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_enderecos/select_by_cliente',
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
     * Busca endereço principal do cliente
     */
    public function buscarPrincipal($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_enderecos/select_principal',
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
     * Busca endereço por ID
     */
    public function buscarPorId($idendereco) {
        $resultado = Database::switchParams(
            ['idendereco' => $idendereco],
            'clientes_enderecos/select_by_id',
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
     * Insere novo endereço
     */
    public function inserir($dados) {
        $params = [
            'idcliente' => $dados['idcliente'],
            'tipo' => $dados['tipo'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'] ?? null,
            'complemento' => $dados['complemento'] ?? null,
            'bairro' => $dados['bairro'] ?? null,
            'cidade' => $dados['cidade'],
            'uf' => $dados['uf'],
            'cep' => $dados['cep'] ?? null,
            'pais' => $dados['pais'] ?? 'BR',
            'principal' => $dados['principal'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes_enderecos/insert',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return Database::getInstance()->lastInsertId();
    }

    /**
     * Atualiza endereço existente
     */
    public function atualizar($idendereco, $dados) {
        $params = [
            'idendereco' => $idendereco,
            'tipo' => $dados['tipo'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'] ?? null,
            'complemento' => $dados['complemento'] ?? null,
            'bairro' => $dados['bairro'] ?? null,
            'cidade' => $dados['cidade'],
            'uf' => $dados['uf'],
            'cep' => $dados['cep'] ?? null,
            'pais' => $dados['pais'] ?? 'BR',
            'principal' => $dados['principal'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes_enderecos/update',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }

    /**
     * Remove todos os endereços principais de um cliente
     */
    public function removerTodosPrincipais($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_enderecos/remove_all_principal',
            true,
            false,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }

    /**
     * Define endereço como principal
     */
    public function definirPrincipal($idendereco) {
        $resultado = Database::switchParams(
            ['idendereco' => $idendereco],
            'clientes_enderecos/set_principal',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }

    /**
     * Exclui endereço
     */
    public function excluir($idendereco) {
        $resultado = Database::switchParams(
            ['idendereco' => $idendereco],
            'clientes_enderecos/delete',
            true,
            true,
            true
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return true;
    }
}