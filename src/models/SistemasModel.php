<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de sistemas
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class SistemasModel extends Model {

    /**
     * Lista todos os sistemas com filtros e paginação
     */
    public function listarTodos($filtros = []) {
        $params = [
            'ativo' => $filtros['ativo'] ?? null,
            'categoria' => $filtros['categoria'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas/select_all',
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
     * Conta total de sistemas com filtros
     */
    public function contarTodos($filtros = []) {
        $params = [
            'ativo' => $filtros['ativo'] ?? null,
            'categoria' => $filtros['categoria'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas/count_all',
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
     * Lista apenas sistemas ativos
     */
    public function listarAtivos() {
        $resultado = Database::switchParams(
            [],
            'sistemas/select_ativos',
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
     * Busca sistema por ID
     */
    public function buscarPorId($idsistema) {
        $resultado = Database::switchParams(
            ['idsistema' => $idsistema],
            'sistemas/select_by_id',
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
     * Pesquisa sistemas por termo
     */
    public function pesquisar($termo, $filtros = []) {
        $params = [
            'termo' => $termo,
            'ativo' => $filtros['ativo'] ?? null,
            'categoria' => $filtros['categoria'] ?? null,
            'limit' => $filtros['limit'] ?? 50,
            'offset' => $filtros['offset'] ?? 0
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas/search',
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
     * Insere novo sistema
     */
    public function inserir($dados) {
        $params = [
            'nome' => $dados['nome'],
            'categoria' => $dados['categoria'] ?? null,
            'descricao' => $dados['descricao'] ?? null,
            'ativo' => $dados['ativo'] ?? 1
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas/insert',
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
     * Atualiza sistema existente
     */
    public function atualizar($idsistema, $dados) {
        $params = [
            'idsistema' => $idsistema,
            'nome' => $dados['nome'],
            'categoria' => $dados['categoria'] ?? null,
            'descricao' => $dados['descricao'] ?? null,
            'ativo' => $dados['ativo']
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas/update',
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
     * Verifica se sistema pode ser excluído (sem assinaturas ativas)
     */
    public function podeExcluir($idsistema) {
        $resultado = Database::switchParams(
            ['idsistema' => $idsistema],
            'sistemas/check_before_delete',
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
     * Exclui sistema
     */
    public function excluir($idsistema) {
        $resultado = Database::switchParams(
            ['idsistema' => $idsistema],
            'sistemas/delete',
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