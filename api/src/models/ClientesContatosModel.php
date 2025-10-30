<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de contatos dos clientes
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class ClientesContatosModel extends Model {

    /**
     * Lista contatos de um cliente
     */
    public function listarPorCliente($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_contatos/select_by_cliente',
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
     * Busca contato principal do cliente
     */
    public function buscarPrincipal($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_contatos/select_principal',
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
     * Busca contato por ID
     */
    public function buscarPorId($idcontato) {
        $resultado = Database::switchParams(
            ['idcontato' => $idcontato],
            'clientes_contatos/select_by_id',
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
     * Insere novo contato
     */
    public function inserir($dados) {
        $params = [
            'idcliente' => $dados['idcliente'],
            'nome' => $dados['nome'],
            'email' => $dados['email'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'cargo' => $dados['cargo'] ?? null,
            'principal' => $dados['principal'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes_contatos/insert',
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
     * Atualiza contato existente
     */
    public function atualizar($idcontato, $dados) {
        $params = [
            'idcontato' => $idcontato,
            'nome' => $dados['nome'],
            'email' => $dados['email'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'cargo' => $dados['cargo'] ?? null,
            'principal' => $dados['principal'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'clientes_contatos/update',
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
     * Remove todos os contatos principais de um cliente
     */
    public function removerTodosPrincipais($idcliente) {
        $resultado = Database::switchParams(
            ['idcliente' => $idcliente],
            'clientes_contatos/remove_all_principal',
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
     * Define contato como principal
     */
    public function definirPrincipal($idcontato) {
        $resultado = Database::switchParams(
            ['idcontato' => $idcontato],
            'clientes_contatos/set_principal',
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
     * Exclui contato
     */
    public function excluir($idcontato) {
        $resultado = Database::switchParams(
            ['idcontato' => $idcontato],
            'clientes_contatos/delete',
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