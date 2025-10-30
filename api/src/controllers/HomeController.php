<?php

/**
 * Classe responsável pelo controle da página inicial
 * Autor: Joaosn
 * Data de início: 23/05/2023
 */

namespace src\controllers;

use Com\Tecnick\Barcode\Type\Square\Aztec\Data;
use \core\Controller as ctrl;
use core\Database;
use src\handlers\Movimento_financeiro;


class HomeController extends ctrl
{

    /**
     * Exibe uma mensagem de boas-vindas e o status da API na página inicial
     */
    public function index()
    {
        ctrl::response(['API' => 'ClickJoias'], 200);
    }

}
