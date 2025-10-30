<?php

namespace core;

header('Content-Type: application/json');

use Exception;
use \src\Config;
use \src\models\Empresa_parametro as EP;
use Throwable;
use \src\handlers\AuthHelper;

class Controller
{

    protected function redirect($url)
    {
        header("Location: " . $this->getBaseUrl() . $url);
        exit;
    }

    public static function getBaseUrl()
    {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':' . $_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;

        return $base;//str_replace('/public','',$base); //n subir
    }

    private function _render($folder, $viewName, $viewData = [])
    {
        if (file_exists('../src/views/' . $folder . '/' . $viewName . '.php')) {
            extract($viewData);
            $render = fn ($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            require '../src/views/' . $folder . '/' . $viewName . '.php';
        }
    }

    private function renderPartial($viewName, $viewData = [])
    {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = [])
    {
        $this->_render('pages', $viewName, $viewData);
    }

    /**
     * recebe um array e verifica item vazios se tiver algum vazio retorna true
     * @param array $error
     */
    public function AllVazio($error)
    {
        foreach ($error as $it => $value) {
            if (empty($it) || is_null($it) || $it == '' || trim($it) == '') {
                return true;
            }
        }
        return false;
    }

    /**
     * Verifica se há campos vazios ou não presentes na lista de campos a serem validados.
     * @param array $campos  Array associativo com os campos e seus respectivos valores a serem verificados.
     * @param array $validar Lista de campos obrigatórios a serem verificados.
     * @return bool Retorna true se todos os campos obrigatórios estiverem preenchidos, caso contrário, rejeita a resposta.
     */
    public static function verificarCamposVazios(array $campos, array $validar): bool
    {
        // Verifica se os campos obrigatórios estão presentes e preenchidos
        foreach ($validar as $key) {
            if (!array_key_exists($key, $campos)) {
                throw new Exception('Campo obrigatório não encontrado: ' . $key);
            }

            $value = $campos[$key];

            if (is_array($value)) {
                if (empty($value)) {
                    throw new Exception('Campo obrigatório vazio: ' . $key);
                }
            } else {
                // evita passar null para trim() convertendo para string
                if (trim((string)$value) === '') {
                    throw new Exception('Campo obrigatório vazio: ' . $key);
                }
            }
        }

        return true;
    }
    



    /**
     * receber boy e retorna array
     * @param bool $valida_body
     */
    public static function getBody($valida_body = true)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (empty($data) && $valida_body) {
            throw new Exception('Nenhum dado foi enviado');
        }
        return $data;
    }

    /**
     * define status e respota para usuario
     * @param array $item
     * @param int $status
     * ex: [
     *   result: [dados pro retorno]
     *   error: [msg de erro] || false
     * ]
     */
    public static function response($item, $status)
    {
        http_response_code($status);
        echo json_encode([
            'result' => $item,
            'error' => ($status > 300) ? true : false
        ]);
        die;
    }

    /**
     * define status e respota para usuario
     * @param Throwable  $msg
     */
    public static function rejectResponse(Throwable $msg)
    {
        $texto = $msg->getMessage() ?: 'Mensagem não informada!';
        http_response_code(400);
        echo json_encode([
            'result' => '',
            'error'  => $texto
        ]);
        die;
    }

    public static function empresa()
    {
        if (empty($_SESSION['empresa']['idempresa'])) {
            $token = self::getRequestToken();
            AuthHelper::getUsuarioLogado($token);
        }
        return $_SESSION['empresa']['idempresa'] ?? null;
    }

    public static function usuario()
    {
        if (empty($_SESSION['empresa']['iduser'])) {
            $token = self::getRequestToken();
            AuthHelper::getUsuarioLogado($token);
        }
        return $_SESSION['empresa']['iduser'] ?? null;
    }

    private static function getRequestToken(): ?string
    {
        $headers = getallheaders();
        $tk = $headers['Authorization'] ?? null;
        $tk2 = isset($_REQUEST['jwt']) ? 'Bearer ' . $_REQUEST['jwt'] : null;
        $token = (!empty($tk) && strlen($tk) > 8) ? $tk : $tk2;
        return $token ? str_replace('Bearer ', '', $token) : null;
    }

    public static function pagamentoLoja($empresa)
    {
        $param = EP::select()->where('idempresa', $empresa)->where('idparametro', 1)->one();
        $servico = !empty($param['valor']) ? json_decode($param['valor'], true) : null;
        return $servico;
    }

    public static function pagamentoOnline($idempresa = null)
    {
        $empresa = $idempresa ?? self::empresa();
        $param = EP::select()->where('idempresa', $empresa)->where('idparametro', 2)->one();
        $servico = !empty($param) ? json_decode($param['valor'], true) : null;
        return $servico;
    }

    /**
     * Grava logs centralizados em api/logs, similar ao padrão usado no Database.
     * Aceita string|array|object e cria a pasta automaticamente.
     * @param string $file Nome do arquivo (ex: 'ibpt_debug.log' ou 'ibpt_debug')
     * @param mixed $content Conteúdo a ser gravado
     * @param bool $append Se true, adiciona ao final do arquivo
     */
    public static function log(string $file, $content, bool $append = true): void
    {
        try {
            // Caminho absoluto para api/logs (ex.: /var/www/html/logs)
            $logsDir = dirname(__DIR__) . '/logs';
            if (!is_dir($logsDir)) {
                @mkdir($logsDir, 0777, true);
            }

            // Garante extensão .log e compõe caminho
            $filename = (strpos($file, '.') === false) ? ($file . '.log') : $file;
            $path = $logsDir . '/' . ltrim($filename, '/');

            // Normaliza conteúdo
            if (is_array($content) || is_object($content)) {
                $content = print_r($content, true);
            } else {
                $content = (string) $content;
            }

            $line = date('Y-m-d H:i:s') . ' ' . $content;
            file_put_contents($path, $line . PHP_EOL, $append ? FILE_APPEND : 0);
        } catch (\Throwable $e) {
            // Evita quebrar fluxo por erro de log
            error_log('LOG FALHOU: ' . $e->getMessage());
        }
    }
}
