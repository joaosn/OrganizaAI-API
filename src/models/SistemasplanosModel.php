<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de planos dos sistemas
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class SistemasplanosModel extends Model {

    /**
     * Lista planos de um sistema
     */
    public function listarPorSistema($idsistema, $filtros = []) {
        $params = [
            'idsistema' => $idsistema,
            'ativo' => $filtros['ativo'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_planos/select_by_sistema',
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
     * Lista apenas planos ativos
     */
    public function listarAtivos() {
        $resultado = Database::switchParams(
            [],
            'sistemas_planos/select_ativos',
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
     * Busca plano por ID
     */
    public function buscarPorId($idplano) {
        $resultado = Database::switchParams(
            ['idplano' => $idplano],
            'sistemas_planos/select_by_id',
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
     * Insere novo plano
     */
    public function inserir($dados) {
        $params = [
            'idsistema' => $dados['idsistema'],
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'ciclo_cobranca' => $dados['ciclo_cobranca'],
            'preco_base_sem_imposto' => $dados['preco_base_sem_imposto'],
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'],
            'ativo' => $dados['ativo'] ?? 1
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_planos/insert',
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
     * Atualiza plano existente
     */
    public function atualizar($idplano, $dados) {
        $params = [
            'idplano' => $idplano,
            'idsistema' => $dados['idsistema'],
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'ciclo_cobranca' => $dados['ciclo_cobranca'],
            'preco_base_sem_imposto' => $dados['preco_base_sem_imposto'],
            'aliquota_imposto_percent' => $dados['aliquota_imposto_percent'],
            'ativo' => $dados['ativo']
        ];

        $resultado = Database::switchParams(
            $params,
            'sistemas_planos/update',
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
     * Verifica se plano pode ser excluído (sem assinaturas ativas)
     */
    public function podeExcluir($idplano) {
        $resultado = Database::switchParams(
            ['idplano' => $idplano],
            'sistemas_planos/check_before_delete',
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
     * Exclui plano
     */
    public function excluir($idplano) {
        $resultado = Database::switchParams(
            ['idplano' => $idplano],
            'sistemas_planos/delete',
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