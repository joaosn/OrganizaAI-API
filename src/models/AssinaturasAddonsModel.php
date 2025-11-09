<?php

namespace src\models;

use core\Model;
use core\Database;
use Exception;

/**
 * Model para gerenciamento de add-ons por assinatura
 * Responsabilidade: APENAS acesso ao banco de dados
 */
class AssinaturasAddonsModel extends Model {

    /**
     * Lista todos os add-ons de uma assinatura
     */
    public function listarPorAssinatura($idassinatura) {
        $resultado = Database::switchParams(
            ['idassinatura' => $idassinatura],
            'assinaturas_addons/select_by_assinatura',
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
     * Busca add-on específico de uma assinatura
     */
    public function buscarPorId($idassinatura, $idaddon) {
        $resultado = Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'idaddon' => $idaddon
            ],
            'assinaturas_addons/select_by_id',
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
     * Insere add-on para uma assinatura
     */
    public function inserir($dados) {
        $params = [
            'idassinatura' => $dados['idassinatura'],
            'idaddon' => $dados['idaddon'],
            'quantidade' => $dados['quantidade'] ?? 1,
            'preco_unitario' => $dados['preco_unitario'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas_addons/insert',
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
     * Atualiza add-on de uma assinatura
     */
    public function atualizar($idassinatura, $idaddon, $dados) {
        $params = [
            'idassinatura' => $idassinatura,
            'idaddon' => $idaddon,
            'quantidade' => $dados['quantidade'] ?? 1,
            'preco_unitario' => $dados['preco_unitario'] ?? null
        ];

        $resultado = Database::switchParams(
            $params,
            'assinaturas_addons/update',
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
     * Remove add-on de uma assinatura
     */
    public function excluir($idassinatura, $idaddon) {
        $resultado = Database::switchParams(
            [
                'idassinatura' => $idassinatura,
                'idaddon' => $idaddon
            ],
            'assinaturas_addons/delete',
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
     * Calcula o total de custo incluindo base + add-ons com impostos
     */
    public function calcularTotal($idassinatura) {
        $resultado = Database::switchParams(
            ['idassinatura' => $idassinatura],
            'assinaturas_addons/calculate_total',
            true,
            false,
            false
        );

        if ($resultado['error']) {
            throw new Exception($resultado['error']);
        }

        return $resultado['retorno'][0] ?? null;
    }
}