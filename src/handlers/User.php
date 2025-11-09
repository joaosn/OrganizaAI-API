<?php

/**
 * desc: helper de manipulação de Usuarios 
 * @autor: joaosn
 * @date: 23/05/2020
 */

namespace src\handlers;

use \src\models\Users;

class User
{
    /**
     * Verifica se o usuário está logado com base no token de sessão.
     *
     * @return bool Retorna true se o usuário estiver logado, caso contrário, retorna false.
     */
    public static function checkLogin()
    {
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $data = Users::getUserToken($token);
            if ($data && count($data) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verifica se o nome de usuário e a senha fornecidos são válidos.
     *
     * @param string $nome O nome de usuário fornecido
     * @param string $senha A senha fornecida
     * @return array|false Retorna um array contendo informações do usuário, incluindo o token, se a autenticação for bem-sucedida; caso contrário, retorna false.
     */
    public static function verifyLogin($nome, $senha)
    {
        $user = Users::getUserName($nome);
        if (!empty($user)) {
            if (password_verify($senha, $user['senha'])) {
                $token = md5(time() . rand(0, 9999) . time());
                Users::saveToken($token, $user['iduser']);
                $user['token'] = $token;
                $_SESSION['token'] = $token;
                return $user;
            }
            return false;
        }
    }

    /**
     * atulizar tema do usuario
     */
    public static function updateTheme($data)
    {
        Users::update(['tema' => $data['tema']])->where('iduser', $data['iduser'])->where('idempresa', $data['idempresa'])->execute();
        $user = Users::select(['iduser', 'nome', 'idempresa', 'tipo', 'tema'])->where('iduser', $data['iduser'])->where('idempresa', $data['idempresa'])->one();
        return $user;
    }

    /**
     * listar usuarios
     */
    public static function listUsers($idempresa){
        $usuarios = Users::select(['iduser','nome','idempresa','tipo','tema'])->where('idempresa', $idempresa)->get();
        return $usuarios;
    }

    /**
     * ATULIZA TOKEN DO USUARIO PARA NULL
     */
    public static function logout(){
        Users::update(['token' => null])->where('token', $_SESSION['token'])->execute();
        unset($_SESSION['token']);
    }
}
