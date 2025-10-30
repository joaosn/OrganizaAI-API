<?php

namespace core;

use \core\Controller as ctrl;
use \src\models\Users;
use \src\handlers\User;
use \src\Config;

class Auth extends ctrl
{
    public  function validaToken($token, $args)
    {
        // if (!$token || empty($_SESSION['token']) || !User::checkLogin()) {
        //     self::VALIDATION();
        // }
    
        // $idempresa = $this->verificarEmpresa($args);
        // $authHeaderParts = explode(' ', $token);
        // $token = $authHeaderParts[1];
        // $autorization = Users::select(['token', 'idempresa'])->where('token', $token)->one();
        
        // if (empty($autorization) ) {
        //     self::VALIDATION();
        // }else if ($idempresa != null && $autorization['idempresa'] != $idempresa ){
        //     self::VALIDATION();
        // }
    }

    public function verificarEmpresa($args)
    {
        $idempresa = null;
        if (isset($args['idempresa'])) {
            $idempresa = $args['idempresa'];
        }

        $payload = ctrl::getBody(false);
        if (isset($payload['idempresa'])) {
            $idempresa = $payload['idempresa'];
        }

        return $idempresa;
    }

    public static function VALIDATION()
    {
        ctrl::response('Sem permissão/token não Informado!/token inválido', 401);
    }
}
