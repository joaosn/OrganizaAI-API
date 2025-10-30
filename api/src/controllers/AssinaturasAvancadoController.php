<?php

namespace src\controllers;

use src\handlers\AssinaturasAvancado;
use core\Controller;
use Exception;

class AssinaturasAvancadoController extends Controller
{
    private $assinaturasAvancadoHandler;

    // Constantes de validação
    const RENOVARCAMPOS = ['idassinatura'];
    const PRORRATACAMPOS = ['idassinatura', 'data_inicio', 'data_fim'];
    const MUDARPLANOCAMPOS = ['idassinatura', 'idplano_novo', 'tipo_mudanca'];
    const CANCELARCAMPOS = ['idassinatura', 'motivo'];
    const HISTORICOCAMPOS = ['idassinatura'];

    public function __construct()
    {
        parent::__construct();
        $this->assinaturasAvancadoHandler = new AssinaturasAvancado();
    }

    /**
     * GET /assinaturas-avancado/para-renovar
     * Obtém assinaturas elegíveis para renovação
     * Query params: dias_antecedencia (padrão: 5)
     */
    public function obterParaRenovar()
    {
        try {
            $diasAntecedencia = $_GET['dias_antecedencia'] ?? 5;

            if (!is_numeric($diasAntecedencia) || $diasAntecedencia <= 0) {
                throw new \Exception("Dias de antecedência deve ser um número positivo");
            }

            $resultado = $this->assinaturasAvancadoHandler->obterAssinaturasParaRenovar($diasAntecedencia);

            Controller::response([
                'total' => count($resultado),
                'assinaturas' => $resultado
            ], 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas-avancado/renovar
     * Renova uma assinatura
     * Body: idassinatura, [novo_preco], [nova_aliquota]
     */
    public function renovar()
    {
        try {
            $data = Controller::getBody();
            Controller::verificarCamposVazios($data, self::RENOVARCAMPOS);

            $idassinatura = $data['idassinatura'];
            $novoPreco = $data['novo_preco'] ?? null;
            $novaAliquota = $data['nova_aliquota'] ?? null;
            $usuarioId = Controller::usuario();

            $resultado = $this->assinaturasAvancadoHandler->renovarAssinatura(
                $idassinatura,
                $novoPreco,
                $novaAliquota,
                $usuarioId
            );

            Controller::response($resultado, 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas-avancado/calcular-prorrata
     * Calcula pro-rata (valor proporcional)
     * Body: idassinatura, data_inicio, data_fim
     */
    public function calcularProrrata()
    {
        try {
            $data = Controller::getBody();
            Controller::verificarCamposVazios($data, self::PRORRATACAMPOS);

            $resultado = $this->assinaturasAvancadoHandler->calcularProrrata(
                $data['idassinatura'],
                $data['data_inicio'],
                $data['data_fim']
            );

            Controller::response($resultado, 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas-avancado/mudar-plano
     * Muda plano da assinatura (upgrade/downgrade)
     * Body: idassinatura, idplano_novo, tipo_mudanca
     */
    public function mudarPlano()
    {
        try {
            $data = Controller::getBody();
            Controller::verificarCamposVazios($data, self::MUDARPLANOCAMPOS);

            $usuarioId = Controller::usuario();

            $resultado = $this->assinaturasAvancadoHandler->mudarPlano(
                $data['idassinatura'],
                $data['idplano_novo'],
                $data['tipo_mudanca'],
                $usuarioId
            );

            Controller::response($resultado, 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * POST /assinaturas-avancado/cancelar
     * Cancela assinatura e calcula reembolso
     * Body: idassinatura, motivo
     */
    public function cancelar()
    {
        try {
            $data = Controller::getBody();
            Controller::verificarCamposVazios($data, self::CANCELARCAMPOS);

            $resultado = $this->assinaturasAvancadoHandler->cancelarAssinatura(
                $data['idassinatura'],
                $data['motivo']
            );

            Controller::response($resultado, 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }

    /**
     * GET /assinaturas-avancado/historico/{idassinatura}
     * Obtém histórico de alterações da assinatura
     * Query params: [data_inicio], [data_fim]
     */
    public function obterHistorico($args)
    {
        try {
            $idassinatura = $args['idassinatura'] ?? null;

            if (!$idassinatura || !is_numeric($idassinatura)) {
                throw new \Exception("ID da assinatura inválido");
            }

            $dataInicio = $_GET['data_inicio'] ?? null;
            $dataFim = $_GET['data_fim'] ?? null;

            $resultado = $this->assinaturasAvancadoHandler->obterHistoricoAlteracoes(
                $idassinatura,
                $dataInicio,
                $dataFim
            );

            Controller::response($resultado, 200);
        } catch (\Exception $e) {
            Controller::rejectResponse($e);
        }
    }
}
