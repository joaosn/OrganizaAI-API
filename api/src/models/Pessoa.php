<?php

namespace src\models;

use core\Database;
use \core\Model;
use PDO;
use src\models\Endereco;
use src\models\Telefone;
use src\models\Pessoa_inadimplente as PessoaInadimplente;
use src\models\Pessoa_tipo as PessoaTipo;
use src\models\Pessoa_credito as PessoaCredito;

/**
 * Classe modelo para a tabela 'Pessoa' do banco de dados.
 */
class Pessoa extends Model
{
    public static function insertPessoa($data)
    {
        $dadosPessoa = [
            'idempresa'         => $data['idempresa'],
            'nome'              => $data['nome']              ?? null,
            'cpf'               => $data['cpf']               ?? null,
            'cnpj'              => $data['cnpj']              ?? null,
            'inscricao_estadual'=> $data['inscricao_estadual']?? null,
            'rg'                => $data['rg']                ?? null,
            'datanascimento'    => $data['datanascimento']    ?? null,
            'genero'            => $data['genero']            ?? null,
            'estadocivil'       => $data['estadocivil']       ?? null,
            'email'             => $data['email']             ?? null,
            'ie'                => $data['ie']                ?? null,
            'tipo_ie'           => $data['tipo_ie']           ?? null,
            'tipo_ie'           => $data['tipo_ie']           ?? null,
            'renda_minima'      => $data['renda_minima']      ?? null,
            'local_trabalho'    => $data['local_trabalho']    ?? null,
        ];

        // Insere a pessoa e obtém o ID gerado
        $idpessoa = Pessoa::insert($dadosPessoa)->execute();
        return $idpessoa;
    }
}
