<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de add-ons dos sistemas
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class SistemasAddonsModel extends Model {

    /**
     * Lista add-ons de um sistema
     */
    public function listarPorSistema($idsistema, $filtros = []) {
        $params = [
            'idsistema' => $idsistema,
            'ativo' => $filtros['ativo'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_addons/select_by_sistema',
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
     * Lista apenas add-ons ativos
     */
    public function listarAtivos() {
        $resultado = Database::switchParams(
            [],
            'sistemas_addons/select_ativos',
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
     * Busca add-on por ID
     */
    public function buscarPorId($idaddon) {
        $resultado = Database::switchParams(
            ['idaddon' => $idaddon],
            'sistemas_addons/select_by_id',
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
     * Insere novo add-on
     */
    public function inserir($dados) {
        $params = [
            'idsistema' => $dados['idsistema'],
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'preco_sem_imposto' => $dados['preco_sem_imposto'],
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'],
            'ativo' => $dados['ativo'] ?? 1
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_addons/insert',
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
     * Atualiza add-on existente
     */
    public function atualizar($idaddon, $dados) {
        $params = [
            'idaddon' => $idaddon,
            'idsistema' => $dados['idsistema'],
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'preco_sem_imposto' => $dados['preco_sem_imposto'],
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'],
            'ativo' => $dados['ativo']
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_addons/update',
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
     * Verifica se add-on pode ser excluído (sem assinaturas ativas)
     */
    public function podeExcluir($idaddon) {
        $resultado = Database::switchParams(
            ['idaddon' => $idaddon],
            'sistemas_addons/check_before_delete',
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
     * Exclui add-on
     */
    public function excluir($idaddon) {
        $resultado = Database::switchParams(
            ['idaddon' => $idaddon],
            'sistemas_addons/delete',
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