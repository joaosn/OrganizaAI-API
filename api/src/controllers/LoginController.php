<?php

/**
 *Controlador de login de usuário.
 *@autor: joaosn
 *@date Inicio: 2023-05-23
 */

namespace src\controllers;

use \core\Controller as ctrl;
use Exception;
use \src\handlers\User;
use \src\models\Users;

class LoginController extends ctrl
{

    /**
     * Verifica e valida o login do usuário
     */
    public function verificarLogin()
    {
        try {
            $dados = ctrl::getBody();
            $nome = $dados['nome'];
            $senha = $dados['senha'];
            if (!empty($nome) && isset($senha)) {
                $infos = User::verifyLogin($nome, $senha);
                if (!empty($infos)) {
                    $infos['pagamento'] = ctrl::pagamentoLoja($infos['idempresa']);
                    $_SESSION['empresa'] = $infos;
                    ctrl::response($infos, 200);
                } else {
                    throw new Exception('Nome e/ou senha não conferem');
                }
            } else {
                throw new Exception('Prencha dados corretamente!');
            }
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }

    /**
     * Realiza o logout do usuário, removendo a sessão e redirecionando para a tela de login
     */
    public function logout()
    {
        try {
            if (!isset($_SESSION['token']) && empty($_SESSION['token'])) {
                throw new Exception('Usuário não está logado');
            }
            User::logout();
            ctrl::response('Logout realizado com sucesso!', 200);
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }

    /**
     * Valida o token do usuário
     */
    public function validaToken()
    {
        try {
            $headers = getallheaders();
            $tk = $headers['Authorization'] ?? null;
            $tk2 = isset($_REQUEST['jwt']) ? 'Bearer ' . $_REQUEST['jwt'] : null;
            $tokenHeader = (!empty($tk) && strlen($tk) > 8) ? $tk : $tk2;

            if (!$tokenHeader) {
                throw new Exception('Token não informado');
            }

            $token = str_replace('Bearer ', '', $tokenHeader);
            $infos = Users::getUserToken($token);
            if (!empty($infos)) {
                $infos['pagamento'] = ctrl::pagamentoLoja($infos['idempresa']);
                $_SESSION['token'] = $token;
                $_SESSION['empresa'] = $infos;
                ctrl::response($infos, 200);
            }

            throw new Exception('Token inválido');
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }

    /**
     * Lista usuários ativos da empresa logada.
     * @return void
     */
    public function listarUsuarios()
    {
        try {
            $usuarios = User::listUsers(ctrl::empresa());
            ctrl::response($usuarios, 200);
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }

    public function atulizaThema(){
        try {
            $data = ctrl::getBody();
            $user = User::updateTheme($data);
            ctrl::response($user, 200);
        } catch (Exception $e) {
            ctrl::rejectResponse($e);
        }
    }
}
