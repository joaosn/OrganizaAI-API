<?php

namespace core;

use \src\Config;
use PDO;
use Exception;

class Database
{
    private static $_pdo;

    /**
     * Retorna uma instância PDO reutilizável.
     * Adapta automaticamente para outros bancos no futuro, se necessário.
     *
     * @return PDO
     */
    public static function getInstance()
    {
        if (!isset(self::$_pdo)) {
            $dsn = Config::DB_DRIVER . ":dbname=" . Config::DB_DATABASE . ";host=" . Config::DB_HOST;
            self::$_pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
            self::$_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }

        return self::$_pdo;
    }

    /**
     * Executa ou retorna uma SQL com substituição de parâmetros.
     *
     * @param array $params Parâmetros nomeados para substituir na SQL.
     * @param string $sqlnome Nome do arquivo SQL (caminho relativo a /SQL).
     * @param bool $exec Executa a SQL (true) ou retorna string final (false).
     * @param bool $log Salva log da execução (SQL, parâmetros, resultado).
     *
     * @return array ['retorno' => mixed, 'error' => string|false]
     */
    public static function switchParams($params, $sqlnome, $exec = false, $log = false,$trasaction = true)
    {
        $res = ['retorno' => false, 'error' => false];
        $pdo = self::getInstance();

        try {
            if(is_array($sqlnome)){
                $sql = $sqlnome['sql'];
            }else{
                $sqlPath = "../SQL/{$sqlnome}.sql";
                if (!file_exists($sqlPath)) {
                    throw new Exception("Arquivo SQL não encontrado: {$sqlnome}");
                }
    
                $sql = file_get_contents($sqlPath);
            }
           
            $sql = self::substituirParametros($sql, $params, $pdo);
            $sql = str_replace('idsql=', 'idsql=E', $sql); // Proteção extra (se aplicável ao seu caso)

            if ($exec) {
                if($trasaction)$pdo->beginTransaction();
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $res['retorno'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt->closeCursor();
                if($trasaction)$pdo->commit();
            } else {
                $res['retorno'] = $sql;
            }

            if ($log) {
                self::logExecucao($sql, $params, $res['retorno']);
            }
        } catch (Exception $e) {
            if($trasaction)$pdo->rollBack();
            self::logErro($e->getMessage(), $sql ?? '', $params ?? []);
            $res['error'] = $e->getMessage();
        }

        unset($pdo);
        return $res;
    }

    /**
     * Substitui os parâmetros nomeados dentro da SQL.
     *
     * @param string $sql
     * @param array $params
     * @param PDO $pdo
     * @return string
     */
    private static function substituirParametros($sql, $params, PDO $pdo)
    {
        if (empty($params)) return $sql;

        foreach ($params as $chave => $valor) {
            // Tratamento seguro: escapa strings e arrays
            if (is_array($valor)) {
                // Converte array para lista separada por vírgulas para SQL
                $valor = implode(',', array_map('intval', $valor));
            } elseif (is_string($valor)) {
                $valor = trim(str_replace('\"', "'", $valor));
            } elseif (is_null($valor)) {
                $valor = 'NULL';
            }

            // Substitui todas as ocorrências exatas do parâmetro
            $sql = preg_replace('/:' . preg_quote($chave, '/') . '\b/i', $valor, $sql);
        }

        return $sql;
    }

    /**
     * Salva um log de execução SQL.
     *
     * @param string $sql
     * @param array $params
     * @param mixed $resultado
     * @return void
     */
    private static function logExecucao($sql, $params, $resultado)
    {
        $log = [
            'data'   => date('Y-m-d H:i:s'),
            'sql'    => $sql,
            'params' => $params,
            'res'    => $resultado
        ];

        file_put_contents(
            '../logs/exec' . date('Y-m-d') . '-sql.txt',
            print_r($log, true),
            FILE_APPEND
        );
    }

    /**
     * Salva um log de erro SQL.
     *
     * @param string $mensagem
     * @param string $sql
     * @param array $params
     * @return void
     */
    private static function logErro($mensagem, $sql, $params)
    {
        $log = [
            'data'   => date('Y-m-d H:i:s'),
            'msg'    => $mensagem,
            'sql'    => $sql,
            'params' => $params
        ];

        file_put_contents(
            '../logs/error' . date('Y-m-d') . '-sql.txt',
            print_r($log, true),
            FILE_APPEND
        );
    }

    // Impede instância direta
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}
