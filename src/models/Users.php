<?php

namespace src\models;

use core\Database as db;
use \core\Model;
use PDO;

/**
 * Classe modelo para a tabela 'users' do banco de dados.
 *
 * @author Seu Nome
 * @date 30-03-2023
 */
class Users extends Model
{
    /**
     * Busca informações do usuário com base no token fornecido.
     *
     * @param string $token O token de autenticação do usuário
     * @return array Retorna um array associativo contendo as informações do usuário
     */
    public static function getUserToken($token)
    {
        $sql = "
            select 
                 u.*
                ,e.nome    as nome_empresa
                ,e.cnpj    as cnpj_empresa
            from users u
                left join empresa e on e.idempresa = u.idempresa 
            where u.token = :token 
        ";
        $sql = db::getInstance()->prepare($sql);
        $sql->bindValue(':token', $token);
        $sql->execute();
        $res = $sql->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Busca informações do usuário com base no nome fornecido.
     *
     * @param string $nome O nome de usuário
     * @return array Retorna um array associativo contendo as informações do usuário
     */
    public static function getUserName($nome)
    {
        $sql = "
            select 
                 u.*
                ,e.nome            as nome_empresa
                ,e.nomefantasia    as nome_fantasia_empresa
                ,e.cnpj            as cnpj_empresa
                ,e.crt
            from users u
                left join empresa e on e.idempresa = u.idempresa 
            where u.nome = :nome 
        ";
        $sql = db::getInstance()->prepare($sql);
        $sql->bindValue(':nome', $nome);
        $sql->execute();
        $res = $sql->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Atualiza o token de autenticação para um usuário específico com base no nome fornecido.
     *
     * @param string $token O novo token de autenticação
     * @param string $iduser O iduser de usuário do usuário cujo token será atualizado
     */
    public static function saveToken($token, $iduser)
    {
        $sql = "UPDATE users SET token = :token WHERE iduser = :iduser";
        $sql = db::getInstance()->prepare($sql);
        $sql->bindValue(':token', $token);
        $sql->bindValue(':iduser', $iduser);
        $sql->execute();
    }
}
